@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@push('style')
<link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">
@endpush
<style>
  .screen .absolute__content .download__button a:first-child {
    margin-right: 96px !important;
  }

  .screen .absolute__content.download__app {
    transform: translate(1%, 0%) !important;
  }
</style>
@section('title', 'Login')

@section('content')

<main class="signin-page form-page">
  <section class="screen  form-page">
    <div class="item">
      <img src="{{ frontendAsset('/frontend/gallery/bg.png') }}" alt="">
    </div>
    <article class="absolute__content">
      <div class="text__wrapper">
        <p class="text-success">Your account is successfully created with GoGo20.</p>
        <div class="download__app absolute__content" style="box-shadow: 0px 0px 71px -3px rgba(80,123,224,0.72) inset;
-webkit-box-shadow: 0px 0px 71px -3px rgba(80,123,224,0.72) inset;
-moz-box-shadow: 0px 0px 71px -3px rgba(80,123,224,0.72) inset; border-radius:10px;">
          <aside>
            <h2 class="card__title card__title--sm">User / Customer App</h2>
            <div class="download__button d-flex justify-content-center align-items-center">
              <a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank" title="playstore">
                <img src="{{ frontendAsset('/frontend/gallery/google.png') }}" alt="">
              </a>
              <a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank" title="istore">
                <img src="{{ frontendAsset('/frontend/gallery/ios.png') }}" alt="">
              </a>
            </div>
          </aside></br>
          <aside>
            <h2 class="card__title card__title--sm">Partner App</h2>
            <div class="download__button d-flex justify-content-center align-items-center">
              <a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
                title="playstore">
                <img src="{{ frontendAsset('/frontend/gallery/google.png') }}" alt="">
              </a>
              <a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank" title="istore">
                <img src="{{ frontendAsset('/frontend/gallery/ios.png') }}" alt="">
              </a>
            </div>
          </aside>
        </div>
        <!-- <ul class="nav " role="tablist">
          <li class="nav-item">
            <a class="active nav-link card__title" data-toggle="tab" href="#tab1" role="tab">
              Login as gogoPartner

            </a>
          </li>
          <li class="nav-item">
            <a class="card card__hr nav-link card__title " data-toggle="tab" href="#tab2">
              Login as User / Customer
            </a>
          </li>

        </ul> -->
        <!-- <div class="tab-content">
          <div class="tab-pane fade show active" id="tab1" role="tabpanel">
            <form action="./account-page.php">
              <div class="form-group">
                <input placeholder="Phone Number" class="form-control" type="tel">
              </div>
              <div class="form-group">
                <input placeholder="Password" class="form-control" type="password">
              </div>
              <div class="form-group">
                <button>Login</button>
              </div>
            </form>


          </div> -->
        <!-- 
          <div class="tab-pane fade " id="tab2" role="tabpanel">
            <form action="./account-page.php">
              <div class="form-group">
                <input placeholder="Phone Number" class="form-control" type="tel">
              </div>
              <div class="form-group">
                <input placeholder="Password" class="form-control" type="password">
              </div>
              <div class="form-group">
                <button>Login</button>
              </div>
            </form>
          </div> -->

        <!-- <div class="download__app">
            <label for=""> or login with </label>
            <div class="download__button d-flex justify-content-center align-items-center">
              <a href="#" target="_blank" title="Google Login">
                <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0)">
                    <path
                      d="M20.4519 0.848145C14.3855 0.848145 8.977 3.50878 5.31269 7.72691C4.19607 9.01359 3.23925 10.4427 2.47815 11.9887L9.21701 17.136C9.998 14.7661 11.5061 12.7324 13.4781 11.2937C15.4309 9.87094 17.8387 9.03027 20.4512 9.03027C23.2736 9.03027 25.8226 10.0307 27.8254 11.6672L33.6517 5.84919C30.1023 2.75731 25.5505 0.848145 20.4519 0.848145Z"
                      fill="#FF7976" />
                    <path
                      d="M2.479 11.988L9.21786 17.1354C9.99886 14.7655 11.5069 12.7318 13.479 11.293C15.4318 9.8703 17.8396 9.02963 20.4521 9.02963C23.2744 9.02963 25.8234 10.0301 27.8263 11.6665L33.6526 5.84855C30.1019 2.75795 25.5507 0.848145 20.4521 0.848145"
                      fill="#E3443A" />
                    <path
                      d="M8.61751 20.8478C8.61751 20.6637 8.63098 20.482 8.63932 20.2998C8.69195 19.1986 8.88768 18.1371 9.21753 17.1354L2.47866 11.988C1.23947 14.5017 0.52073 17.3164 0.439872 20.2998C0.434738 20.4827 0.423828 20.6643 0.423828 20.8478C0.423828 21.032 0.434738 21.2143 0.43923 21.3972C0.520088 24.3767 1.23883 27.1901 2.47481 29.7012L9.21175 24.5442C8.88511 23.5476 8.69066 22.4907 8.63932 21.3965C8.63098 21.2143 8.61751 21.0327 8.61751 20.8478Z"
                      fill="#F4D72C" />
                    <path
                      d="M2.47571 29.7012L9.21265 24.5442C8.886 23.5476 8.69156 22.4907 8.64022 21.3965C8.63188 21.2136 8.6184 21.032 8.6184 20.8472C8.6184 20.663 8.63188 20.4814 8.64022 20.2991C8.69284 19.1979 8.88857 18.1365 9.21842 17.1348L2.47892 11.988"
                      fill="#F7B92B" />
                    <path
                      d="M27.1035 30.9173C25.2983 32.0545 23.0246 32.666 20.4519 32.666C18.9464 32.666 17.5121 32.3798 16.1901 31.8709C15.2166 31.4961 14.3079 30.995 13.4781 30.3917C11.5067 28.9562 9.998 26.9289 9.21701 24.5609L2.47815 29.7076C3.23796 31.251 4.19287 32.6782 5.30756 33.963C8.97187 38.1856 14.3843 40.8482 20.4519 40.8482C22.2872 40.8482 24.0911 40.603 25.8136 40.1256C28.677 39.3311 31.3158 37.893 33.5009 35.8638L27.1035 30.9173Z"
                      fill="#59C96E" />
                    <path
                      d="M2.479 29.7076C3.23882 31.251 4.19372 32.6782 5.30841 33.963C8.97272 38.1856 14.3851 40.8482 20.4527 40.8482C22.2881 40.8482 24.092 40.603 25.8144 40.1256C28.6778 39.3311 31.3167 37.893 33.5018 35.8638L27.1037 30.918C25.2985 32.0551 23.0248 32.6667 20.4521 32.6667C18.9466 32.6667 17.5123 32.3805 16.1903 31.8716C15.2168 31.4968 14.3081 30.9956 13.4783 30.3924"
                      fill="#40A557" />
                    <path
                      d="M39.1149 16.9974H37.9142H20.6387V20.2998V21.3972V24.6983H31.1946C30.6574 27.2652 29.1956 29.5986 27.1035 30.9173L33.5016 35.8632C35.2561 34.2344 36.7135 32.1905 37.7621 29.8264C38.9121 27.2363 39.5699 24.1342 39.5699 20.7253C39.5699 19.5445 39.3883 18.2809 39.1149 16.9974Z"
                      fill="#0FAEF4" />
                    <path
                      d="M33.5016 35.8632C35.2561 34.2344 36.7263 32.1674 37.7749 29.8033C38.9249 27.2132 39.5763 24.1226 39.5763 20.7144C39.5763 19.5329 39.3882 18.2809 39.1148 16.9974H37.9655"
                      fill="#4087ED" />
                    <path d="M20.6387 20.2062V21.3972V24.6983H30.9064" fill="#4087ED" />
                  </g>
                  <defs>
                    <clipPath id="clip0">
                      <rect width="40" height="40" fill="white" transform="translate(0 0.848145)" />
                    </clipPath>
                  </defs>
                </svg>

              </a>
              <a href="#" target="_blank" title="Facebook Login">
                <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <g clip-path="url(#clip0)">
                    <path
                      d="M35 0.848145H5C2.24167 0.848145 0 3.08981 0 5.84814V35.8481C0 38.6048 2.24167 40.8481 5 40.8481H35C37.7567 40.8481 40 38.6048 40 35.8481V5.84814C40 3.08981 37.7567 0.848145 35 0.848145Z"
                      fill="#3B5999" />
                    <path
                      d="M27.5 20.8481V15.8481C27.5 14.4681 28.62 14.5981 30 14.5981H32.5V8.34814H27.5C23.3567 8.34814 20 11.7048 20 15.8481V20.8481H15V27.0981H20V40.8481H27.5V27.0981H31.25L33.75 20.8481H27.5Z"
                      fill="white" />
                  </g>
                  <defs>
                    <clipPath id="clip0">
                      <rect width="40" height="40" fill="white" transform="translate(0 0.848145)" />
                    </clipPath>
                  </defs>
                </svg>


              </a>

              <a href="#" target="_blank" title="Linked Login">
                <svg width="40" height="41" viewBox="0 0 40 41" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M36.3817 0.848145H3.61832C1.62 0.848145 0 2.46814 0 4.46647V37.2297C0 39.2281 1.62 40.8481 3.61832 40.8481H36.3816C38.38 40.8481 40 39.2281 40 37.2297V4.46647C40 2.46814 38.38 0.848145 36.3817 0.848145ZM12.3777 35.3868C12.3777 35.9684 11.9063 36.4398 11.3247 36.4398H6.84241C6.26084 36.4398 5.78942 35.9684 5.78942 35.3868V16.5971C5.78942 16.0156 6.26084 15.5442 6.84241 15.5442H11.3247C11.9063 15.5442 12.3777 16.0156 12.3777 16.5971V35.3868ZM9.08356 13.773C6.73183 13.773 4.82534 11.8665 4.82534 9.51474C4.82534 7.16301 6.73183 5.25652 9.08356 5.25652C11.4353 5.25652 13.3418 7.16301 13.3418 9.51474C13.3418 11.8665 11.4354 13.773 9.08356 13.773ZM35.8021 35.4716C35.8021 36.0063 35.3686 36.4398 34.8339 36.4398H30.0241C29.4894 36.4398 29.0559 36.0063 29.0559 35.4716V26.6582C29.0559 25.3434 29.4416 20.8968 25.62 20.8968C22.6557 20.8968 22.0544 23.9404 21.9337 25.3063V35.4716C21.9337 36.0063 21.5003 36.4398 20.9655 36.4398H16.3136C15.779 36.4398 15.3454 36.0063 15.3454 35.4716V16.5123C15.3454 15.9777 15.779 15.5442 16.3136 15.5442H20.9655C21.5002 15.5442 21.9337 15.9777 21.9337 16.5123V18.1516C23.0329 16.5021 24.6664 15.2289 28.1444 15.2289C35.8462 15.2289 35.8021 22.4243 35.8021 26.3778V35.4716Z"
                    fill="#0077B7" />
                </svg>

              </a>
              <a href="#" target="_blank" title="Apple Login">
                <svg width="33" height="42" viewBox="0 0 33 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M32.6348 30.4224C32.5264 30.7283 32.4215 31.0568 32.3007 31.3796C31.3007 34.0873 29.7826 36.4915 27.9405 38.6947C27.585 39.1236 27.1707 39.5119 26.743 39.8719C25.8976 40.5875 24.9246 41.0198 23.7993 41.0593C22.9539 41.0864 22.1424 40.9329 21.3602 40.6146C20.7936 40.3832 20.236 40.1248 19.6615 39.916C17.7969 39.2387 15.9424 39.3279 14.1083 40.0525C13.4468 40.3144 12.7944 40.6033 12.1206 40.8302C11.3395 41.0921 10.5291 41.1982 9.70405 41.0424C8.98394 40.9081 8.35413 40.5729 7.76946 40.1406C7.0437 39.6033 6.4342 38.9487 5.8642 38.2556C3.16547 34.9643 1.35391 31.243 0.47916 27.0747C0.102173 25.2823 -0.0784195 23.4753 0.0321935 21.6434C0.161994 19.4469 0.672169 17.3554 1.84602 15.4705C3.31898 13.1014 5.39692 11.5494 8.13064 10.9094C9.77516 10.5223 11.3745 10.6803 12.9423 11.2841C13.7674 11.6002 14.5947 11.9128 15.4232 12.2232C16.1862 12.5099 16.9458 12.5099 17.7099 12.2176C18.5553 11.8948 19.4019 11.5731 20.2507 11.2582C21.1209 10.941 22.0058 10.6814 22.9291 10.608C24.3106 10.4963 25.6583 10.6758 26.9698 11.1148C28.8288 11.7367 30.3413 12.8372 31.4835 14.44C31.514 14.484 31.5479 14.5325 31.5704 14.5675C28.69 16.5755 27.05 19.228 27.2881 22.8195C27.5285 26.4133 29.4631 28.8445 32.6348 30.4224ZM16.4774 10.1633C17.3273 10.1859 18.1445 10.0222 18.9312 9.7096C22.5194 8.28517 24.3953 4.48934 24.3388 1.58632C24.3343 1.35268 24.3185 1.11903 24.3083 0.848145C23.9393 0.903451 23.6029 0.929411 23.2801 1.01068C20.6604 1.65291 18.6445 3.11458 17.3194 5.48147C16.544 6.86752 16.1128 8.33935 16.2449 9.94775C16.2539 10.1182 16.316 10.1611 16.4774 10.1633Z"
                    fill="#4E4E4E" fill-opacity="0.4" />
                </svg>

              </a>
            </div>
          </div> -->

        <!-- <p class="para small"><a href="#">Forget password?</a></p>
          <p class="para small">Don't have account? <a href="./signup.php" class="text__active">Create account.</a></p> -->
      </div>
      </div>

      <div class="back">

        <button onclick="window.location.href='/'">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x"
            viewBox="0 0 16 16">
            <path
              d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
          </svg>
        </button>
      </div>

    </article>

  </section>
</main>

@endsection

@push('script')
<script src="{{ frontendAsset('/frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
@endpush