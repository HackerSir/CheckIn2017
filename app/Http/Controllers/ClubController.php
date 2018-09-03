<?php

namespace App\Http\Controllers;

use App\Booth;
use App\Club;
use App\ClubType;
use App\DataTables\ClubsDataTable;
use App\Services\FileService;
use App\Services\ImgurImageService;
use App\Services\StudentService;
use App\Services\UserService;
use App\Student;
use App\User;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ClubController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param ClubsDataTable $dataTable
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(ClubsDataTable $dataTable)
    {
        return $dataTable->render('club.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('club.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ImgurImageService $imgurImageService
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function store(Request $request, ImgurImageService $imgurImageService)
    {
        $this->validate($request, [
            'number'       => 'nullable',
            'name'         => 'required',
            'club_type_id' => 'nullable|exists:club_types,id',
            'description'  => 'nullable|max:300',
            'extra_info'   => 'nullable|max:300',
            'url'          => 'nullable|url',
            'image_file'   => 'image',
        ]);

        $club = Club::create(array_merge($request->all(), [
            'number' => strtoupper($request->get('number')),
        ]));

        //上傳圖片
        $uploadedFile = $request->file('image_file');
        if ($uploadedFile) {
            $imgurImage = $imgurImageService->upload($uploadedFile);
            $club->imgurImage()->save($imgurImage);
        }

        //更新攤位
        $attachBoothIds = (array) $request->get('booth_id');
        $attachBooths = Booth::whereDoesntHave('club')->whereIn('id', $attachBoothIds)->get();
        $club->booths()->saveMany($attachBooths);

        //更新攤位負責人
        $attachUserIds = (array) $request->get('user_id');
        $attachUsers = User::whereDoesntHave('club')->whereIn('id', $attachUserIds)->get();
        $club->users()->saveMany($attachUsers);

        return redirect()->route('clubs.show', $club)->with('success', '社團已新增');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Club $club
     * @return \Illuminate\Http\Response
     */
    public function edit(Club $club)
    {
        return view('club.edit', compact('club'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Club $club
     * @param ImgurImageService $imgurImageService
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, Club $club, ImgurImageService $imgurImageService)
    {
        $this->validate($request, [
            'number'       => 'nullable',
            'name'         => 'required',
            'club_type_id' => 'nullable|exists:club_types,id',
            'description'  => 'nullable|max:300',
            'extra_info'   => 'nullable|max:300',
            'url'          => 'nullable|url',
            'image_file'   => 'image',
        ]);

        $club->update(array_merge($request->all(), [
            'number' => strtoupper($request->get('number')),
        ]));

        //上傳圖片
        $uploadedFile = $request->file('image_file');
        if ($uploadedFile) {
            //刪除舊圖
            if ($club->imgurImage) {
                $club->imgurImage->delete();
            }
            //上傳新圖
            $imgurImage = $imgurImageService->upload($uploadedFile);
            $club->imgurImage()->save($imgurImage);
        }

        //更新攤位
        $oldBoothIds = (array) $club->booths->pluck('id')->toArray();
        $newBoothIds = (array) $request->get('booth_id');
        $detachBoothIds = array_diff($oldBoothIds, $newBoothIds);
        $attachBoothIds = array_diff($newBoothIds, $oldBoothIds);

        /** @var User[] $detachUsers */
        $detachBooths = Booth::whereIn('id', $detachBoothIds)->get();
        foreach ($detachBooths as $detachBooth) {
            $detachBooth->club()->dissociate();
            $detachBooth->save();
        }
        $attachBooths = Booth::whereDoesntHave('club')->whereIn('id', $attachBoothIds)->get();
        $club->booths()->saveMany($attachBooths);

        //更新攤位負責人
        $oldUserIds = (array) $club->users->pluck('id')->toArray();
        $newUserIds = (array) $request->get('user_id');
        $detachUserIds = array_diff($oldUserIds, $newUserIds);
        $attachUserIds = array_diff($newUserIds, $oldUserIds);

        /** @var User[] $detachUsers */
        $detachUsers = User::whereIn('id', $detachUserIds)->get();
        foreach ($detachUsers as $detachUser) {
            $detachUser->club()->dissociate();
            $detachUser->save();
        }
        $attachUsers = User::whereDoesntHave('club')->whereIn('id', $attachUserIds)->get();
        $club->users()->saveMany($attachUsers);

        return redirect()->route('clubs.show', $club)->with('success', '社團已更新');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Club $club
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy(Club $club)
    {
        $club->delete();

        return redirect()->route('club.index')->with('success', '社團已刪除');
    }

    public function getImport()
    {
        return view('club.import');
    }

    public function postImport(
        Request $request,
        FileService $fileService,
        StudentService $studentService,
        UserService $userService
    ) {
        //檢查匯入檔案格式為xls或xlsx
        $this->validate($request, [
            'import_file' => 'required|mimes:xls,xlsx',
        ]);

        $uploadedFile = $request->file('import_file');
        $uploadedFilePath = $uploadedFile->getPathname();
        $spreadsheet = $fileService->loadSpreadsheet($uploadedFilePath);
        if (!$spreadsheet) {
            return redirect()->back()->withErrors(['importFile' => '檔案格式限xls或xlsx']);
        }
        $successCount = 0;
        $skipCount = 0;
        $invalidNidCount = 0;
        foreach ($spreadsheet->getAllSheets() as $sheetId => $sheet) {
            foreach ($sheet->getRowIterator() as $rowNumber => $row) {
                //忽略第一列
                if ($rowNumber == 1) {
                    continue;
                }
                //該列資料
                $rowData = [];
                for ($col = 1; $col <= 9; $col++) {
                    $cell = $sheet->getCellByColumnAndRow($col, $row->getRowIndex());
                    $colData = $cell->getValue();
                    if (!($colData instanceof RichText)) {
                        $colData = trim($cell->getFormattedValue());
                    }
                    $rowData[] = $colData;
                }
                //資料
                $name = $rowData[0];
                $number = strtoupper($rowData[1]);
                $clubTypeName = $rowData[2];
                $boothName = $rowData[3];
                $ownerNIDs = [];
                for ($i = 0; $i < 5; $i++) {
                    $ownerNIDs[$i] = strtoupper($rowData[$i + 4]);
                }

                //資料必須齊全
                if (empty($name)) {
                    $skipCount++;
                    continue;
                }
                //社團類型
                if (!empty($clubTypeName)) {
                    /** @var ClubType $clubType */
                    $clubType = ClubType::query()->firstOrCreate([
                        'name' => $clubTypeName,
                    ], [
                        'color'      => '#000000',
                        'is_counted' => true,
                    ]);
                }

                //建立社團
                /** @var Club $club */
                $club = Club::query()->updateOrCreate([
                    'name' => $name,
                ], [
                    'number'       => $number,
                    'club_type_id' => isset($clubType) ? $clubType->id : null,
                ]);

                //攤位
                /** @var Booth $booth */
                $booth = Booth::whereName($boothName)->first();
                if ($booth) {
                    $booth->update(['club_id' => $club->id]);
                }

                //攤位負責人
                $ownerNIDs = array_filter($ownerNIDs);
                foreach ($ownerNIDs as $ownerNID) {
                    //試著找出學生
                    /** @var Student $student */
                    $student = $studentService->findByNid($ownerNID);
                    if (!$student) {
                        //NID無效
                        $invalidNidCount++;
                        continue;
                    }
                    //找出使用者
                    $user = $userService->findOrCreateAndBind($student);
                    //設定為負責人
                    $user->club()->associate($club);
                    $user->save();
                }

                $successCount++;
            }
        }

        return redirect()->route('club.index')
            ->with('success', "匯入完成(成功:{$successCount}/跳過:{$skipCount}/NID無效:{$invalidNidCount})");
    }

    public function downloadImportSample()
    {
        $path = resource_path('sample/club_import_sample.xlsx');

        return response()->download($path);
    }
}
