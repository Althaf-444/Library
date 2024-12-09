<?php
require_once('../layouts/login_header.php');
?>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner">
          <!-- Register Card -->
          <div class="card">
            <div class="card-body">
              <h4 class="mb-2">Adventure starts here 🚀</h4>
              <p class="mb-4">Make your app management easy and fun!</p>

              <form id="formAuthentication" class="mb-3" action="<?= url('services/auth_reg.php') ?>" method="POST">
                <div class="mb-3">
                  <label for="username" class="form-label">Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    autofocus
                    required
                    style=  " border-color:#03c3ec ; box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); outline: none; "

                  />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input 
                  type="email" 
                  class="form-control"
                   id="email" 
                   name="email" 
                   placeholder="Enter your email"
                   style=  " border-color:#03c3ec ; box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); outline: none; "
                   required
                    />
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Password</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    required
                    style=  " border-color:#03c3ec ; box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); outline: none; "

                    />
                    <span class="input-group-text cursor-pointer" style=" border-color:#03c3ec"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" style=" background-color:#03c3ec; border-color:#03c3ec" />
                    <label class="form-check-label" for="terms-conditions"  style=" color:#03c3ec">
                      I agree to
                      <a href="javascript:void(0);"  style=" color:#03c3ec">privacy policy & terms</a>
                    </label>
                  </div>
                </div>
                <button class="btn btn-info d-grid w-100">Sign up</button>
              </form>

              <p class="text-center">
                <span  style=" color:#03c3ec">Already have an account?</span>
                <a href="login.php">
                  <span  style=" color:#03c3ec">Sign in instead</span>
                </a>
              </p>
            </div>
          </div>
          <!-- Register Card -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    
    <?php
require_once('../layouts/login_footer.php');
?>