<?php

namespace App\Http\Controllers;

use App\Club;
use App\Services\ImgurImageService;
use Illuminate\Http\Request;

class OwnClubController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $club = $this->getOwnClub();

        return view('own-club.edit', compact('club'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ImgurImageService $imgurImageService
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, ImgurImageService $imgurImageService)
    {
        $club = $this->getOwnClub();

        $this->validate($request, [
            'description' => 'nullable|max:300',
            'url'         => 'nullable|url',
            'image_file'  => 'image',
        ]);

        $club->update($request->only(['description', 'url']));

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

        return redirect()->route('clubs.show', $club)->with('global', '社團已更新');
    }

    /**
     * 取得自己所負責的社團
     *
     * @return Club
     */
    private function getOwnClub()
    {
        $user = auth()->user();
        $club = $user->club;
        if (!$club) {
            abort(403);
        }

        return $club;
    }
}
