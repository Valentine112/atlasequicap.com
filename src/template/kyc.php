<?php if($user['kyc'] === 0): ?>
<style>
    .kyc{
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100vh;
        z-index: 2;
        background: rgba(0, 0, 0, 0.9);
    }
    .kyc > div{
        background: #13141E;
        color: #fff;
        height: 90vh;
        margin-top: 3%;
    }
    .kyc .file{
        border-radius: 30px;
        background-color: #111;
    }
</style>
    <div class="kyc">
        <div class="col-11 col-md-6 mx-auto p-4">
            <h3>KYC</h3>
            <span>Please verify your kyc to use our services</span>
            <hr>

            <div class="col-11 px-1">
                <p style="text-decoration: underline; color: #444;">Documents Upload</p>

                <div class="px-2">
                    <div class="col-11 my-5">
                        <label for="govt">Government Approved ID</label>
                        <input type="file" class="form-control" id="govt" data-bs-theme="dark">
                    </div>
                    <div class="col-11 my-4">
                        <label for="govt">User Passport Photo</label>
                        <input type="file" class="form-control" id="passport" data-bs-theme="dark">
                    </div>
                    <div class="col-11 my-5">
                        <button class="btn btn-primary" onclick="kyc(this)">Upload</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>