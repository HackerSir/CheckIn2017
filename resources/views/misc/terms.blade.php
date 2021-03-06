@extends('layouts.base')

@section('title', '服務條款 Terms of Service')

@section('main_content')
    @if($showAgreementInfo)
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>使用本站服務前，您必須同意服務條款
        </div>
    @endif
    <p class="text-right">修訂日期：2017年08月16日</p>
    <div class="card">
        <div class="card-body">
            <h2 class="card-title">歡迎使用{{ config('site.name') }}</h2>
            <p>
                歡迎各位參加社團博覽會打卡集點抽獎活動網站（以下簡稱「本站」），本站由逢甲大學課外活動組（以下簡稱「課外組」）委託逢甲大學黑客社（以下簡稱「本社」）開發及管理，只要您使用了本站，即表示您同意了本條款，故還請冗詳閱。</p>

            <h2 class="card-title">使用網站</h2>
            <p>當各位在使用網站時，您必須遵守所有的規範。</p>
            <p>您不得濫用本站。舉例來說，您不應該「干擾」或「影響」本站的運行，亦不得透過非本社提供的介面來存取本站，假若我們發現您未遵守本條款，我們可能會暫停或終止您使用本站的權利。</p>
            <p>使用本站並不會將本站或存取資訊的智慧財產權授權給您。除非相關法律允許或原作者授權，您不得於本站以外使用本站資源。</p>
            <p>本站可以於手機端使用，但使用時請注意周遭環境，以免發生危險。</p>

            <h2 class="card-title">隱私權及個人資料</h2>
            <p>本站經逢甲大學資訊處（以下簡稱「資訊處」）授權使用校方 NID 登入系統，所有個人資料皆從校方伺服器獲取，中間的連線皆已加密，藉此確保傳輸安全。</p>
            <p>
                本站會儲存使用者的資料，包括學號、姓名、系級、性別、打卡項目及時間、抽獎編號等，所有資料僅供課外組於抽獎後核對使用，不會顯示給非擁有者。課外組及本社全體工作人員禁止於非活動必要狀況下公開任何使用者資料，亦不得挪作他用或交給第三方單位。</p>
            <p>本站活動資料於活動結束後保存於本社主機，保存期限為一年，保存期間亦須符合本條款之規定。</p>
            <p>本站保存之使用者資料若與校方系統不同，以校方系統為準。</p>

            <h2 class="card-title">著作權保護</h2>
            <p>本站顯示部分內容為課外組及各社團單位提供，授權本站使用，本站不主張相關圖片及文字之著作權，其著作權歸屬原作者所有。</p>
            <p>欲於本站外使用相關圖片或文字，請自行向課外組或該社團單位接洽，本站或本社不做中介轉接洽。</p>
            <p>若本站有屬於您擁有的相關圖片及文字，且您並未授權本站使用，請立即通知我們，我們會盡速協助移除這些圖片或文字。</p>

            <h2 class="card-title">免責聲明</h2>
            <p>本社於合理的範圍提供本站供各位使用，希望您盡情使用，但關於本站，有些事情本社不予以保證。</p>
            <p>本社會致力於維持本站的可用性，但本站不擔保校方 NID 登入系統的可用性，亦不擔保校方資料之正確性，在這一方面若有任何問題，請向資訊處或註冊課務組洽詢。</p>
            <p>本社不擔保活動的規則及流程，所有規則及流程由課外組處理，課外組保有活動最終解釋權，對活動有任何疑問亦請於當天至服務台詢問。</p>
            <p>本社不擔保本站顯示圖文之著作權，詳細請閱讀上面的「著作權保護」段落。</p>
            <p>本社不擔保因校園Wi-Fi網路造成的資料損失，或任何不正常操作所造成的資料毀損或遺失。</p>

            <h2 class="card-title">關於本條款的說明</h2>
            <p>本社可能會因反映規則異動或法律條文來修正本條款，修正時將會另行通知，該修正是否朔及既往以課外組公告為準。</p>
            <p>當發生特定條款無法執行的情況時，並不會影響任何其他條款。</p>
            <p>若本條款與現行法律或課外組訂定規則有所牴觸，以現行法律或課外組訂定規則為準。</p>
            <p>因本站所生之法律糾紛或相關主張，應完全由台中地方法院審理，您與本社皆同意上述法院之屬人管轄權。</p>
            <br>
            <br>
            <p>最後，再次誠摯的感謝您使用本站並閱讀本條款，祝您使用愉快！</p>
            <br>
            <br>
            <p>Regards,</p>
            <p><a href="https://www.facebook.com/HackerSir.tw" target="_blank">逢甲大學黑客社</a></p>
        </div>
    </div>
    @if($showAgreementInfo)
        <div class="card mt-2">
            <div class="card-body">
                {{ bs()->openForm('post', route('terms.agree')) }}
                <div class="text-center" style="font-size: 1.75rem">
                    <label style="vertical-align: middle">
                        <input class="mr-2" style="height: 2rem; width: 2rem" name="agree" type="checkbox" value="1"
                               required>我已閱讀並同意服務條款
                    </label>
                </div>
                <div class="row mt-2">
                    <div class="mx-auto">
                        {{ bs()->submit('確認', 'primary')->prependChildren(fa()->icon('check')->addClass('mr-2')) }}
                    </div>
                </div>
                {{ bs()->closeForm() }}
            </div>
        </div>
    @endif
@endsection
