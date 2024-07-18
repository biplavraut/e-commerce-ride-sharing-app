<footer>
    <div class="section__rule">
        <div class="subcribe">
            <form action="#! ">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Enter Your Email">
                    <button type="submit">Subscribe</button>
                </div>
            </form>
        </div>

        <nav class="nav nav-fill">
            <li class="nav__item p-0 active">
                <a href="/" class="logo nav__link">
                    <img src="{{ frontendAsset('/frontend/gallery/gogo20.png') }}" alt="">
                </a>
            </li>
            <li class="nav__item ml-auto active">
                <a href="/" class="logo nav__link">About</a>
            </li>
            <li class="nav__item active">
                <a href="/" class="logo nav__link">gogoDriver</a>
            </li>
            <li class="nav__item active">
                <a href="/" class="logo nav__link">gogoDelivery</a>
            </li>
            <li class="nav__item active">
                <a href="/" class="logo nav__link">gogoPartner</a>
            </li>
            <li class="nav__item active">
                <a href="/" class="logo nav__link">gogoFranchise</a>
            </li>
            <li class="nav__item active">
                <a href="/" class="logo nav__link">gogoNewsroom</a>
            </li>
            <li class="nav__item ">
                <a href="/career" class="logo nav__link" target="_blank">Career</a>
            </li>
        </nav>
        <hr>
        <div class="details__section d-flex">
            <article>
                <h2 class="card__title">Contact Us</h2>
                <p class="para">support@gogo20.com</p>
            </article>
            <article>
                <h2 class="card__title">Location</h2>
                <p class="para">Goreto Tower, Dhumbarahi, Chandol, Kathmandu, Nepal</p>
            </article>
            <article class="ml-auto">
                <h2 class="card__title card__title--sm">User/ Customer App</h2>
                <div class="download__button d-flex justify-content-center align-items-center">
                    <a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank"
                        title="playstore">
                        <img src="{{ frontendAsset('/frontend//gallery/google.png') }}" alt="">
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.gogo20.user" target="_blank"
                        title="istore">
                        <img src="{{ frontendAsset('/frontend/gallery/ios.png' ) }}" alt="">
                    </a>
                </div>
            </article>
            <article>
                <h2 class="card__title card__title--sm">Partner App</h2>

                <div class="download__button d-flex justify-content-center align-items-center">
                    <a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
                        title="playstore">
                        <img src="{{ frontendAsset('/frontend//gallery/google.png') }}" alt="">
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=com.gogo20.driver" target="_blank"
                        title="istore">
                        <img src="{{ frontendAsset('/frontend/gallery/ios.png') }}" alt="">
                    </a>
                </div>
            </article>

        </div>
    </div>
    <div class="copy--right">
        <div class="section__rule">
            <div class="d-flex justify-content-between align-items-center">
                <p class="para">&copy; Copyright {{ date('Y') }}. Allrights reserved. <a href="/terms-and-condition"
                        target="_blank">Terms & Condition</a> | <a href="/privacy-policy" target="_blank">Privacy
                        Policy </a> </p>
                <p class="para"></p>
                <div class="d-flex justify-content-around align-items-center social--media">

                </div>
            </div>
        </div>
    </div>
</footer>


<!--jquery  -->
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
<script src="https://kit.fontawesome.com/021b940a03.js" crossorigin="anonymous"></script>


<!-- fancybox -->
<!-- <script type="text/javascript" src="./css/fancybox/jquery.fancybox.min.js"></script> -->
<!-- owl -->
<script type="text/javascript" src="{{ frontendAsset('/frontend/css/slick/slick.min.js') }}"></script>

<script src="{{ frontendAsset('/frontend/js/hammerjs.js') }}"></script>
<!-- bootstrap -->
<script type="text/javascript" src="{{ frontendAsset('/frontend/css/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/css/bootstrap/js/bootstrap.min.js') }}"></script>


<!-- commom js -->
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/commonjs.js') }}"></script>

@stack('script')