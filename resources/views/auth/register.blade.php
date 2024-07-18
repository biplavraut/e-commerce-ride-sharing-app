    @extends('frontend.layouts.master')
    <?php $currentpage = 'home';  ?>
    @push('style')
    <link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">
    @endpush
    @section('title', 'Signup')
    @section('content')
    <style>
        .signup-page .screen .absolute__content {
            max-width: 900px;
        }

        #strength:not(:empty) {
            background: #fffa82;
            border-radius: 8px;
        }

        .errorOccurs:not(:empty) {
            margin: 0;
            float: left;
            width: 100%;
            background: #fffa82;
            border-radius: 8px;
            padding: 4px 10px;
            font-size: 12px;
            line-height: 01;
            color: #990f0f;
            margin-bottom: 10px;
            font-weight: bold;
            text-align: left;
        }

        .showError {
            animation: shake 0.9s;
        }

        @keyframes shake {

            10%,
            90% {
                transform: translate3d(-1px, 0, 0);
            }

            20%,
            80% {
                transform: translate3d(2px, 0, 0);
            }

            30%,
            50%,
            70% {
                transform: translate3d(-4px, 0, 0);
            }

            40%,
            60% {
                transform: translate3d(4px, 0, 0);
            }
        }

        .hideError {
            display: none;
        }
    </style>
    <main class="signin-page form-page signup-page gogocares--form">
        <section class="screen signin-page form-page">
            <div class="item">
                <img src="{{ frontendAsset('/frontend/gallery/bg.png') }}" alt="">
            </div>
            <!-- absolute part -->
            <article class="absolute__content">
                <div class="text__wrapper">
                    <ul class="nav " role="tablist">
                        <li class="nav-item">
                            <a class="active nav-link card__title" data-toggle="tab" href="#tab1" role="tab">
                                SignUp as gogoVendor
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- data-toggle="tab" href="#tab2" role="tab"-->
                            <a class="card card__hr nav-link card__title " data-toggle="tab" href="#tab2">
                                SignUp as User / Customer
                            </a>
                        </li>
                        <li class="nav-item">
                            <!-- data-toggle="tab" href="#tab2" role="tab"-->
                            <a class="card card__hr nav-link card__title " data-toggle="tab" href="#tab3">
                                Biker / Taxi Rider / Delivery Partner
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="tab1" role="tabpanel">
                            <form method="post" action="register">
                                @csrf
                                <div class="form-group--wrapper">
                                    <div class="form-group">
                                        <input placeholder="Business Name" class="form-control" type="text"
                                            name="businessName" required id="businessName">
                                        <div id="businessNameError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="text" class="hidden-item" name="whichform" hidden="hidden"
                                                value="first" />
                                            <select class="form-control" name="businesstype" title="Type/Category" required
                                                aria-required="true">
                                                <option value="">Choose Business Type/Category</option>
                                                @foreach ($services as $service)
                                                <option value="{{ $service->slug }}">{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div id="typeError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-line">
                                            <select class="form-control" name="partnershipType" title="Partnership Type"
                                                required="" aria-required="true">
                                                <option value="">Choose Partnership Type</option>
                                                <option value="single">Single</option>
                                                <option value="joint">Joint</option>
                                                <option value="pvt. ltd.">Pvt. Ltd.</option>
                                                <option value="limited">Limited</option>
                                            </select>
                                        </div>
                                        <div id="partnershipTypeError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input placeholder="First Name" required="required" class="form-control"
                                                type="text" name="fname">
                                            <div id="firstNameError" class="errorOccurs showError hideError"></div>
                                            <input typpe="text" name="for" value="vendor" hidden />
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input placeholder="Last Name" class="form-control" type="text" name="lname">
                                            <div id="lastNameError" class="errorOccurs showError hideError"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Email" class="form-control" type="email" name="email">
                                        <div id="emailError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Mobile Number" class="form-control" type="number"
                                            id="mobileNumber" name="tel">
                                        <div id="phoneError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <input placeholder="City" class="form-control" type="text" name="city">
                                            <div id="cityError" class="errorOccurs showError hideError"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input placeholder="Business Registered Address" class="form-control"
                                                type="text" name="fulladdress">
                                            <div id="addressError" class="errorOccurs showError hideError"></div>
                                        </div>
                                    </div>
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Password" required="" autocomplete="new-password"
                                            aria-required="true">
                                        <div id="passwordError" class="errorOccurs showError hideError"></div>
                                    </div><br />
                                    <div class="form-line">
                                        <input type="password" class="form-control" name="password_confirmation"
                                            placeholder="Confirm Password" required="" autocomplete="new-password"
                                            aria-required="true">
                                        <div id="passwordConfirmationError" class="errorOccurs showError hideError"></div>
                                    </div><br />
                                    <label> How did you hear about gogo20?</label>
                                    <div class="form-group">
                                        <select class="form-select" name="hearfrom">
                                            <option selected value="friends">Friends</option>
                                            <option value="ads">Advertisement</option>
                                            <option value="facebook">Facebook</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <i class="material-icons ">unfold_more</i>
                                        <div id="hearFromError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="termsandcondition"
                                            value="termsandcondition" id="accept" checked>
                                        <label class="form-check-label" for="accept">
                                            I agree <a href="/terms-and-condition/vendor" target="_blank">terms and conditions</a> associated with
                                            this service and as a user.
                                        </label>
                                    </div>
                                    <div id="termsError" class="errorOccurs showError hideError"></div>
                                    <span><div id="emailOrPhoneError" class="errorOccurs showError hideError"></div></span>
                                </div>
                                <div class="otp--wrapper">
                                    <p>Enter 6 Digit Verification
                                        code sent to your Mobile Number </p>
                                    <div class="form-group otp">
                                        <input type="number" name="otpCode[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCode[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCode[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCode[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCode[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCode[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                    </div>
                                    <div id="otpError" class="errorOccurs showError hideError"></div>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="firstSignUpButtonProcessing"
                                        style="display:none;">Processing...</button>
                                    <button type="button" id="firstSignUpButton" class="submit">Sign Up</button>
                                </div>
                            </form>
                        </div>
                        <div class="tab-pane fade " id="tab2" role="tabpanel">
                            <form method="post">
                                <div class="form-group--wrapper">
                                    <div class="form-row">
                                        <div class="form-group col-6">
                                            <input placeholder="First Name" name="cfname" class="form-control" type="text">
                                            <div id="cfnameError" class="errorOccurs showError hideError"></div>
                                        </div>
                                        <div class="form-group col-6">
                                            <input placeholder="Last Name" name="clname" class="form-control" type="text">
                                            <input type="text" class="hidden-item" name="whichform" hidden="hidden"
                                                value="second" />
                                            <div id="clnameError" class="errorOccurs showError hideError"></div>
                                        </div>
                                    </div>
                                    <label> Gender</label>
                                    <div class="form-group">
                                        <select class="form-select" name="cgender">
                                            <option selected disabled value="">Select Gender</option>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Others</option>
                                        </select>
                                        <i class="material-icons ">unfold_more</i>
                                        <div id="cgenderError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Email" class="form-control" type="email" name="cemail">
                                        <div id="cemailError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Mobile Number" name="ctel" class="form-control" type="number">
                                        <div id="ctelError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Your Current Address" class="form-control" type="text"
                                            name="caddress">
                                        <div id="caddressError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group">
                                        <input placeholder="Password" name="cpassword" class="form-control" type="password">
                                        <div id="cpasswordError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <label> How did you hear about gogo20?</label>
                                    <div class="form-group">
                                        <select class="form-select" name="chearfrom">
                                            <option selected value="friends">Friends</option>
                                            <option value="ads">Advertisement</option>
                                            <option value="facebook">Facebook</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <i class="material-icons ">unfold_more</i>
                                        <div id="chearfromError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <label> Do you have Referral Code?</label>
                                    <div class="form-group">
                                        <input placeholder="Referral Code" name="crefercode" class="form-control"
                                            type="text" maxlength="10" minlength="10">
                                        <div id="crefercodeError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="acceptuser" checked>
                                        <label class="form-check-label" for="accept">
                                            I agree <a href="/terms-and-condition/user" target="_blank">terms and conditions</a> associated with
                                            this service and as a user.
                                        </label>
                                    </div>
                                    <div id="acceptuserError" class="errorOccurs showError hideError"></div>
                                </div>
                                <div class="otp--wrapper">
                                    <p>Enter 6 Digit Verification
                                        code sent to your Mobile Number </p>
                                    <div class="form-group otp">
                                        <input type="number" name="otpCodeSecond[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCodeSecond[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCodeSecond[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCodeSecond[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCodeSecond[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                        <input type="number" name="otpCodeSecond[]" class="form-control" maxlength="1"
                                            oninput="this.value=this.value.replace(/[^0-9]/g,'');" max="9" min="0">
                                    </div>
                                    <div id="otpErrorsecond" class="errorOccurs showError hideError"></div>
                                </div>
                                <div class="form-group">
                                    <button type="button" id="secondSignUpButtonProcessing"
                                        style="display:none;">Processing...</button>
                                    <button type="button" id="secondSignUpButton" class="submit">Sign Up</button>
                                </div>

                            </form>
                        </div>
                        <div class="tab-pane fade " id="tab3" role="tabpanel">
                            <form method="post">
                                <div class="form-row">
                                    <div class="form-group col-6">
                                        <input placeholder="First Name" name="dfname" class="form-control" type="text">
                                        <div id="dfnameError" class="errorOccurs showError hideError"></div>
                                    </div>
                                    <div class="form-group col-6">
                                        <input placeholder="Last Name" name="dlname" class="form-control" type="text">
                                        <input type="text" class="hidden-item" name="whichform" hidden="hidden"
                                            value="third" />
                                        <div id="dlnameError" class="errorOccurs showError hideError"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <select class="form-select" name="dgender">
                                        <option selected="" value="">Gender</option>
                                        <option value="male">Male </option>
                                        <option value="female">Female </option>
                                        <option value="other">Other </option>
                                    </select>
                                    <i class="material-icons ">unfold_more</i>
                                    <div id="dgenderError" class="errorOccurs showError hideError"></div>
                                </div>

                                <div class="form-group">
                                    <div class="input-groups">
                                        <input placeholder="Password" name="dpassword" class="form-control" type="password">
                                        <div id="dpasswordError" class="errorOccurs showError hideError"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input placeholder="Mobile Number" name="dtel" class="form-control" type="tel">
                                    <div id="dtelError" class="errorOccurs showError hideError"></div>
                                </div>
                                <div class="form-group">
                                    <input placeholder="Your Current Address" class="form-control" type="text"
                                        name="daddress">
                                    <div id="daddressError" class="errorOccurs showError hideError"></div>
                                </div>
                                <div class="form-row box__wrapper">
                                    <label class="text-muted col-12">Intrested in</label>
                                    <div class="form-group col-6">
                                        <div class="form-check ">
                                            <input name="dintrested" class="form-check-input" type="radio" value="rider"
                                                id="selectV1">
                                            <label class="list-group-item list-group-item-action form-check-label"
                                                for="selectV1">
                                                Ride
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group col-6">
                                        <div class="form-check">
                                            <input name="dintrested" class="form-check-input" type="radio" value="delivery"
                                                id="selectV2">
                                            <label class="list-group-item list-group-item-action form-check-label"
                                                for="selectV2">
                                                Delivery
                                            </label>
                                        </div>
                                    </div>
                                    <div id="dintrestedError" class="errorOccurs showError hideError"></div>
                                </div>
                                <label class="text-muted"> Subscription Type</label>
                                <div class="form-group">
                                    <select class="form-select" name="dsubscription">
                                        <option selected disabled value="">Select Subscription Type</option>
                                        @foreach ($packages as $package)
                                        <option value="{{ $package->id }}">{{ $package->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="material-icons ">unfold_more</i>
                                    <div id="dsubscriptionError" class="errorOccurs showError hideError"></div>
                                </div>
                                <label class="text-muted"> How did you hear about gogo20?</label>
                                <div class="form-group">
                                    <select class="form-select" name="dhearfrom">
                                        <option selected="" value="friends">Friends</option>
                                        <option value="ads">Advertisement</option>
                                        <option value="facebook">Facebook</option>
                                        <option value="other">Other</option>
                                    </select>
                                    <i class="material-icons ">unfold_more</i>
                                    <div id="dhearfromError" class="errorOccurs showError hideError"></div>
                                </div>
                                <label> Do you have Referral Code?</label>
                                <div class="form-group">
                                    <input placeholder="Referral Code" name="drefercode" class="form-control" type="text"
                                        maxlength="10" minlength="10">
                                    <div id="drefercodeError" class="errorOccurs showError hideError"></div>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="acceptuserthird" checked>
                                    <label class="form-check-label" for="accept">
                                        I agree <a href="/terms-and-condition/rider" target="_blank">terms and conditions</a> associated with
                                        this service and as a user.
                                    </label>
                                </div>
                                <div id="acceptuserthirdError" class="errorOccurs showError hideError"></div>
                                <div class="form-group">
                                    <button type="button" id="thirdSignUpButton" class="submit">Apply</button>
                                </div>
                        </div>
                        {{-- <p class="para small">Already have account? <a href="./signin.php" class="text__active">LogIn</a>
                        </p> --}}
                        </form>
                    </div>
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
    <script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ frontendAsset('/frontend/css/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ frontendAsset('/frontend/css/bootstrap/js/bootstrap.min.js') }}"></script>
    <script>
    $(document).ready(function(){
        if(window.location.hash != "") {
            $('a[href="' + window.location.hash + '"]').click()
        }
        function validateEmail(email) {
            const re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        }

        $('button.submit').click(function(e){
            // var for = document.getElementsByName("for")[0].value;
            let elemMe = this;
            let elem =$(this).parents('form').find('.otp--wrapper');
            var isErrorExist = 0;
            // var valuecheck = elem.find("")getElementsByName("for")[0].value;
            var valuecheck = $(this).parents('form').find(".hidden-item")[0].value;
            if(valuecheck == "second"){
                var fname = document.getElementsByName("cfname")[0].value;
                var lname = document.getElementsByName("clname")[0].value;
                var gender = document.getElementsByName("cgender")[0].value;
                var address = document.getElementsByName("caddress")[0].value;
                var refercode = document.getElementsByName("crefercode")[0].value;
                var ctel = document.getElementsByName("ctel")[0].value;
                var hearfrom = document.getElementsByName("chearfrom")[0].value;
                var cpassword = document.getElementsByName("cpassword")[0].value;
                var cemail = document.getElementsByName("cemail")[0].value;
                var tacu = document.getElementById("acceptuser").checked;
                if(fname.length < 3){
                    isErrorExist++;
                    $("#cfnameError").html("First name must be at least 3 character");
                    $("#cfnameError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#cfnameError").html("");
                    $("#cfnameError").addClass("hideError");
                }
                if(lname.length < 2){
                    isErrorExist++;
                    $("#clnameError").html("Last name must be at least 2 character");
                    $("#clnameError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#clnameError").html("");
                    $("#clnameError").addClass("hideError");
                }
                if(ctel.length <= 9){
                    isErrorExist++;
                    $("#ctelError").html("Mobile number must have 10 digits");
                    $("#ctelError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#ctelError").html("");
                    $("#ctelError").addClass("hideError");
                }

                if(cpassword.length <= 5){
                    isErrorExist++;
                    $("#cpasswordError").html("Password must be greather than 5 character long");
                    $("#cpasswordError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#cpasswordError").html("");
                    $("#cpasswordError").addClass("hideError");
                }
                if(hearfrom.length <= 2){
                    isErrorExist++;
                    $("#chearfromError").html("Please Specify, from where you hear about GoGo20.");
                    $("#chearfromError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#chearfromError").html("");
                    $("#chearfromError").addClass("hideError");
                }
                if(!tacu){
                        isErrorExist++;
                        $("#acceptuserError").html("Terms & condition must need to accept");
                        $("#acceptuserError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#acceptuserError").html("");
                    $("#acceptuserError").addClass("hideError");
                }
                    if(cemail.length <= 6){
                        isErrorExist++;
                        $("#cemailError").html("Email should be at least of 6 character");
                        $("#cemailError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#cemailError").html("");
                        $("#cemailError").addClass("hideError");
                    }
                    if(!validateEmail(cemail))
                    {
                        isErrorExist++;
                        $("#cemailError").html("Please provide a valid email address");
                        $("#cemailError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#cemailError").html("");
                        $("#cemailError").addClass("hideError");
                    }
                ///Get input otp 
                var otpConcatsecond = "";
                $("input[name='otpCodeSecond[]']").each(function() {
                    otpConcatsecond += $(this).val();
                });

                if(isErrorExist == -8 && otpConcatsecond.length == 0){
                    $("#secondSignUpButtonProcessing").hide();
                    $("#secondSignUpButton").show();
                    $.ajax({
                        type:'GET',
                        url:'/api/auth/send-otp',
                        data: {
                        "_token": "{{ csrf_token() }}",
                        "countryCode":"977",
                        "phone":ctel
                    },
                    error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        for (const eachError in err.errors) {
                        $("#"+eachError+"Error").html(err.errors[eachError][0]);
                        $("#"+eachError+"Error").removeClass("hideError");
                        }
                    },
                    success:function(data) {
                        if(data.existedUser){
                        $("#acceptuserError").html("This phone number is already in use.");
                        $("#acceptuserError").removeClass("hideError");
                        }else{
                            $("#secondSignUpButtonProcessing").hide();
                            $("#secondSignUpButton").show();
                            elem.show('300');
                            elem.find('input:first-child').focus();
                            elem.prev().find('input , select').attr('disabled','true');
                        }
                    
                    },
                    });
                }else if(isErrorExist == -8 && otpConcatsecond.length == 6){
                        $.ajax({
                        type:'POST',
                        url:'/api/auth/register',
                        data: {
                            "token": "{{ csrf_token() }}",
                            "firstName": fname,
                            "lastName":lname,
                            "email ":cemail,
                            "countryCode":"977",
                            "phone":ctel,
                            "password":cpassword,
                            "password_confirmation":cpassword,
                            "image":"",
                            "hearfrom":hearfrom,
                            "from":"normal",
                            "otp":otpConcatsecond,
                            'gender' : gender,
                            'address' : address,
                            'referCode' : refercode,
                            'web' : 'web',
                        },
                        error: function(xhr, status, error) {
                        var err = eval("(" + xhr.responseText + ")");
                        if(err.message == "otp doesnot match")
                        {
                            $("#otpErrorsecond").html("OTP code does not match");
                            $("#otpErrorsecond").removeClass("hideError");
                        }else{
                            for (const eachError in err.errors) {
                                $("#"+eachError+"Error").html(err.errors[eachError][0]);
                                $("#"+eachError+"Error").removeClass("hideError");
                            }
                        }
                        },
                        success:function(data) {
                            console.log(data);
                            // window.location.href = "/login";
                        },
                        });
                    }
                
            }else if(valuecheck == "third"){
                isErrorExist = 0;
                var fname = document.getElementsByName("dfname")[0].value;
                var lname = document.getElementsByName("dlname")[0].value;
                var gender = document.getElementsByName("dgender")[0].value;
                var address = document.getElementsByName("daddress")[0].value;
                var subscription = document.getElementsByName("dsubscription")[0].value;
                var refercode = document.getElementsByName("drefercode")[0].value;
                var password = document.getElementsByName("dpassword")[0].value;
                var tel = document.getElementsByName("dtel")[0].value;
                var intrestedIn = $("input[type='radio'][name='dintrested']:checked").val();
                var hearform = document.getElementsByName("dhearfrom")[0].value;
                var tacu = document.getElementById("acceptuserthird").checked;


                if(!tacu){
                        isErrorExist++;
                        $("#acceptuserthirdError").html("Terms & condition must need to accept");
                        $("#acceptuserthirdError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#acceptuserthirdError").html("");
                    $("#acceptuserthirdError").addClass("hideError");
                }
                if(fname.length < 3){
                    isErrorExist++;
                    $("#dfnameError").html("First name must be at least 3 character");
                    $("#dfnameError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dfnameError").html("");
                    $("#dfnameError").addClass("hideError");
                }
                if(lname.length < 2){
                    isErrorExist++;
                    $("#dlnameError").html("Last name must be at least 2 character");
                    $("#dlnameError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dlnameError").html("");
                    $("#dlnameError").addClass("hideError");
                }
                if(gender.length <= 3){
                    isErrorExist++;
                    $("#dgenderError").html("Please select you gender");
                    $("#dgenderError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dgenderError").html("");
                    $("#dgenderError").addClass("hideError");
                }
                if(password.length <= 6){
                    isErrorExist++;
                    $("#dpasswordError").html("Password must be greater than 6 character");
                    $("#dpasswordError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dpasswordError").html("");
                    $("#dpasswordError").addClass("hideError");
                }
                if(tel.length != 10){
                    isErrorExist++;
                    $("#dtelError").html("Mobile number must have 10 digits");
                    $("#dtelError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dtelError").html("");
                    $("#dtelError").addClass("hideError");
                }
                if(intrestedIn != "bike" && intrestedIn != "car"){
                    isErrorExist++;
                    $("#dintrestedError").html("Select your intrested riding platform");
                    $("#dintrestedError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dintrestedError").html("");
                    $("#dintrestedError").addClass("hideError");
                }
                if(hearform.length <= 2){
                    isErrorExist++;
                    $("#dhearfromError").html("Please select a option");
                    $("#dhearfromError").removeClass("hideError");
                }else{
                    isErrorExist--;
                    $("#dhearfromError").html("");
                    $("#dhearfromError").addClass("hideError");
                }
                if(isErrorExist == -8){
                    $.ajax({
                    type:'POST',
                    url:'/api/driver/registerweb',
                    data: {
                    "_token": "{{ csrf_token() }}",
                    "firstName": fname,
                    "lastName":lname,
                    "countryCode":"977",
                    "phone":tel,
                    "password":password,
                    "password_confirmation":password,
                    "heard_from":hearform,
                    "interestedIn":intrestedIn,
                    "gender":gender,
                    'subscriptionPackageId' : subscription,
                    'address' : address,
                    'referCode' : refercode
                    },
                    error: function(xhr, status, error) {
                        alert("Error Occurs while processing");
                    },
                    success:function(data) {
                    window.location.href = "/login";
                    },
                    });
                }

            }else if(valuecheck == "first"){
                    var password = document.getElementsByName("password")[0].value;
                    var confirmPassword = document.getElementsByName("password_confirmation")[0].value;
                    var bname = document.getElementsByName("businessName")[0].value;
                    var btype = document.getElementsByName("businesstype")[0].value;
                    var partnershiptype = document.getElementsByName("partnershipType")[0].value;
                    var fname = document.getElementsByName("fname")[0].value;
                    var lname = document.getElementsByName("lname")[0].value;
                    var email = document.getElementsByName("email")[0].value;
                    var tel = document.getElementsByName("tel")[0].value;
                    var city = document.getElementsByName("city")[0].value;
                    var fulladdress = document.getElementsByName("fulladdress")[0].value;
                    var hearfrom = document.getElementsByName("hearfrom")[0].value;
                    var tac = document.getElementById("accept").checked;
                    
                    if(btype.length <= 2){
                        isErrorExist++;
                        $("#typeError").html("Please select your business type");
                        $("#typeError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#typeError").html("");
                        $("#typeError").addClass("hideError");
                    }
                    if(partnershiptype.length <= 2){
                        isErrorExist++;
                        $("#partnershipTypeError").html("Please select partnership type");
                        $("#partnershipTypeError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#partnershipTypeError").html("");
                        $("#partnershipTypeError").addClass("hideError");
                    }
                    if(fname.length < 3){
                        isErrorExist++;
                        $("#firstNameError").html("First name must be at least 3 character");
                        $("#firstNameError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#firstNameError").html("");
                        $("#firstNameError").addClass("hideError");
                    }
                    if(lname.length < 2){
                        isErrorExist++;
                        $("#lastNameError").html("Last name must be at least 2 character");
                        $("#lastNameError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#lastNameError").html("");
                        $("#lastNameError").addClass("hideError");
                    }
                    if(email.length > 0 && email.length <= 6){
                        isErrorExist++;
                        $("#emailError").html("Email should be at least of 6 character");
                        $("#emailError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#emailError").html("");
                        $("#emailError").addClass("hideError");
                    }
                    if(email.length > 0 && !validateEmail(email))
                    {
                        isErrorExist++;
                        $("#emailError").html("Please provide a valid email address");
                        $("#emailError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#emailError").html("");
                        $("#emailError").addClass("hideError");
                    }
                    if(tel.length != 10){
                        isErrorExist++;
                        $("#phoneError").html("Mobile number must have 10 digits");
                        $("#phoneError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#phoneError").html("");
                        $("#phoneError").addClass("hideError");
                    }
                    if(city.length <= 3){
                        isErrorExist++;
                        $("#cityError").html("City must be greather than 3 character");
                        $("#cityError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#cityError").html("");
                        $("#cityError").addClass("hideError");
                    }
                    if(fulladdress.length <= 3){
                        isErrorExist++;
                        $("#addressError").html("Address must be greather than 3 character");
                        $("#addressError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#addressError").html("");
                        $("#addressError").addClass("hideError");
                    }
                    if(hearfrom.length <= 2){
                        isErrorExist++;
                        $("#hearFromError").html("Please specify!!! From where you heard about GoGo20 ?");
                        $("#hearFromError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#hearFromError").html("");
                        $("#hearFromError").addClass("hideError");
                    }
                    if(!tac){
                        isErrorExist++;
                        $("#termsError").html("Terms & condition must need to accept");
                        $("#termsError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#termsError").html("");
                        $("#termsError").addClass("hideError");
                    }
                    if(password.length <= 6){
                        isErrorExist++;
                        $("#passwordError").html("Password must be greater than 6 character");
                        $("#passwordError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#passwordError").html("");
                        $("#passwordError").addClass("hideError");
                    }
                    if(password != confirmPassword){
                        isErrorExist++;
                        $("#passwordConfirmationError").html("Confirm password not matched");
                        $("#passwordConfirmationError").removeClass("hideError");
                    }else{
                        isErrorExist--;
                        $("#passwordConfirmationError").html("");
                        $("#passwordConfirmationError").addClass("hideError");
                    }
                    ///Get input otp 
                    var otpConcat = "";
                    $("input[name='otpCode[]']").each(function() {
                        otpConcat += $(this).val();
                    });
                    if(isErrorExist == -13 && otpConcat.length != 6){
                        $("#firstSignUpButtonProcessing").show();
                        $("#firstSignUpButton").hide();
                    $.ajax({
                    type:'GET',
                    url:'/api/vendor/send-otp',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "countryCode":"977",
                        "phone":tel
                    },
                    error: function(xhr, status, error) {
                        console.log(status)
                        if(status == false){
                            alert(message)
                            $("#firstSignUpButtonProcessing").hide();
                            $("#firstSignUpButton").show();
                        }
                        var err = eval("(" + xhr.responseText + ")");
                        for (const eachError in err.errors) {
                            $("#"+eachError+"Error").html(err.errors[eachError][0]);
                            $("#"+eachError+"Error").removeClass("hideError");
                        }
                    },
                    success:function(data) {
                        console.log(data.status)
                        if(!data.existedUser){
                            $("#firstSignUpButtonProcessing").hide();
                            $("#firstSignUpButton").show();
                            elem.show('300');
                            elem.find('input:first-child').focus();
                            elem.prev().find('input , select').attr('disabled','true');
                        }else{
                            $("#firstSignUpButtonProcessing").hide();
                            $("#firstSignUpButton").show();
                            $("#emailOrPhoneError").html("Already Registered: Email or Phone Exist");
                            $("#emailOrPhoneError").removeClass("hideError");
                            // alert('Email or Phone Exist')
                        }                        
                    },
                    });
                    }else if(isErrorExist == -13 && otpConcat.length == 6){
                    $.ajax({
                        type:'POST',
                        url:'/api/vendor/register',
                        data: {
                            "_token": "{{ csrf_token() }}",
                            "businessName": bname,
                            "firstName":fname,
                            "lastName":lname,
                            "email":email,
                            "countryCode":"977",
                            "phone":tel,
                            "password":password,
                            "password_confirmation":password,
                            "image":"",
                            "city":city,
                            "address":fulladdress,
                            "partnershipType":partnershiptype,
                            "otp":otpConcat,
                            "type":btype,
                            "heardFrom":hearfrom
                        },
                        error: function(xhr, status, error) {
                            var err = eval("(" + xhr.responseText + ")");
                            if(err.message == "otp does not match")
                            {
                                $("#otpError").html("OTP code does not match");
                                $("#otpError").removeClass("hideError");
                            }else{
                                for (const eachError in err.errors) {
                                    $("#"+eachError+"Error").html(err.errors[eachError][0]);
                                    $("#"+eachError+"Error").removeClass("hideError");
                                }
                            }
                        },
                        success:function(data) {
                            window.location.href = "/login";
                        },
                    });
                }
            }
        })
        $(".otp--wrapper input").keyup(function () {
            $(this).next().focus();
        });
        $(".otp--wrapper input:last-child").keyup(function () {
            // $(this).parents('.otp--wrapper').next().find('button')[0].type='submit';
            // $(this).parents('.otp--wrapper').next().find('button')[0].focus();
        });
    });
    </script>
    @endpush