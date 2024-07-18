@extends('frontend.layouts.master')

<?php $currentpage = 'home';  ?>

@push('style')
<link rel="stylesheet" href="{{ frontendAsset('/frontend/css/otherstyle.css') }}">

<style>
	.inner__screen {
		background-image: url("/frontend/gallery/bg.png");
	}

	b {
		color: #4e4e4e;
	}

	.terms-para {
		text-align: justify;
		text-justify: inter-word;
	}
</style>

@endpush

@section('title', 'Terms & Condition')

@section('content')

<main class=" terms-page inner-page " id="main-content">
	<section class="inner__screen">
		<div class="section__rule">
			<h2 class="section__title">Terms & Condition</h2>
		</div>
	</section>
	<section class="terms--page__content white">
		<div class="section__rule">
			<h2 class="section__title">Our Terms & Condition</h2>

			<div class="para">
				<div class="terms-para">
					@if (request('type') == 'user')
					{!! $company->user_tac !!}
					@elseif (request('type') == 'rider')
					{!! $company->rider_tac !!}
					@elseif (request('type') == 'vendor')
					{!! $company->vendor_tac !!}
					@else
					<div id="pf1" class="pf w0 h0" data-page-no="1">
						<div class="pc pc1 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2  y4 ff2 fs1 fc1 sc0 ls0 ws0">provided by me in accordance with the
									Privacy Policy and I fully comply with<span class="_ _0"></span> Terms &amp; </div>
								<div class="t m0 x2  y5 ff2 fs1 fc1 sc0 ls0 ws0">Conditions which I have read and
									understand. </div>
								<div class="t m0 x2  y6 ff3 fs1 fc2 sc0 ls0 ws0">This Privacy Policy and its Addendum(s)
									(“<span class="ff1">Policy</span>”) describe how <span class="ff2">iSelect Pvt. Ltd.</span></div>
								<div class="t m0 x2  y7 ff2 fs1 fc2 sc0 ls0 ws0">, its respective subsidiaries,
									affiliates, associat<span class="_ _1"></span>ed compani<span class="_ _0"></span>es
									and
									jointly controlled </div>
								<div class="t m0 x2  y8 ff3 fs1 fc2 sc0 ls0 ws0">entities (collectively “<span
										class="ff1">iSelect</span><span class="ls1">”,</span><span class="ff1">
										gogo20<span class="ff4">”<span class="ff2">) collect, use, process and disclose
												your
												Perso<span class="_ _1"></span>nal </span></span></span></div>
								<div class="t m0 x2  y9 ff2 fs1 fc2 sc0 ls0 ws0">Data through the use of gogo20<span
										class="ff3">’s mobile applications and websites</span> <span
										class="ff3">(respectively “<span class="ff1">Apps</span>” <span
											class="_ _0"></span>and </span></div>
								<div class="t m0 x2  ya ff3 fs1 fc2 sc0 ls0 ws0">“<span
										class="ff1">Websites</span>”),<span class="ff2"> as well as products, features
										and other services globally, operated by
									</span></div>
								<div class="t m0 x2  yb ff2 fs1 fc2 sc0 ls0 ws0">iSelect <span
										class="ff3">(collectively,
										“<span class="ff1">Services</span>”).</span> </div>
								<div class="t m0 x2  yc ff2 fs1 fc2 sc0 ls0 ws0">This Policy applies to our users,
									customers, passengers, vendors, agents, distributors, </div>
								<div class="t m0 x2  yd ff2 fs1 fc2 sc0 ls0 ws0">suppliers, partners (such as driver and
									vendor partners), contractors and service providers </div>
								<div class="t m0 x2  ye ff2 fs1 fc2 sc0 ls2 ws0">(co<span class="ff3 ls0">llectively
										“<span class="ff1">you</span>”, “<span class="ff1">your</span>” or “<span
											class="ff1">yours</span>”).<span class="ff2"> </span></span></div>
								<div class="t m0 x2  yf ff3 fs1 fc2 sc0 ls0 ws0">“<span class="ff1">Personal
										Data</span>” is
									any information which can be used to identify you <span class="ff2">or from which
										you
										are </span></div>
								<div class="t m0 x2  y10 ff2 fs1 fc2 sc0 ls0 ws0">identifiable. This includes but is not
									limited to your name, nati<span class="_ _1"></span>onality, telephone number,
								</div>
								<div class="t m0 x2  y11 ff2 fs1 fc2 sc0 ls0 ws0">bank and credit card details, personal
									interests, email address, your image, government-</div>
								<div class="t m0 x2  y12 ff2 fs1 fc2 sc0 ls0 ws0">issued identification numbers,
									biometric
									data, race, date of birth, marital status, religion, </div>
								<div class="t m0 x2  y13 ff2 fs1 fc2 sc0 ls0 ws0">health information, vehicle and
									insurance
									information, employment information, financial </div>
								<div class="t m0 x2  y14 ff2 fs1 fc2 sc0 ls0 ws0">information etc. </div>
								<div class="t m0 x3 h5 y15 ff1 fs1 fc1 sc0 ls3 ws0">1.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Collection of Personal Data
										</span></span></div>
								<div class="t m0 x4 h6 y16 ff6 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y17 ff2 fs1 fc1 sc0 ls0 ws0">We collect Personal Data about you in
									the
									ways li<span class="_ _1"></span>sted below. We may also combine </div>
								<div class="t m0 x4  y18 ff2 fs1 fc1 sc0 ls0 ws0">the collected Personal Data with other
									Personal <span class="_ _1"></span>Data in our possession.<span class="_ _0"></span>
									If
									you have </div>
								<div class="t m0 x4  y19 ff2 fs1 fc1 sc0 ls0 ws0">or are a party to multiple
									relationships
									with <span class="_ _1"></span>us (for example if <span class="_ _0"></span>you use
									our
									Services </div>
								<div class="t m0 x4  y1a ff2 fs1 fc1 sc0 ls0 ws0">across our various business verticals,
									or
									if you are both a driver partner/delivery </div>
								<div class="t m0 x4  y1b ff2 fs1 fc1 sc0 ls4 ws0">pa<span class="ls0">rtner as well as a
										passenger on our transport vertical or a c<span class="_ _1"></span>ustomer on
										our
										other </span></div>
								<div class="t m0 x4  y1c ff2 fs1 fc1 sc0 ls0 ws0">business verticals), we will link your
									Personal Data collected across your various </div>
								<div class="t m0 x4  y1d ff2 fs1 fc1 sc0 ls0 ws0">capacities to facilitate your use of
									our
									Services and for the Purposes described </div>
								<div class="t m0 x4  y1e ff2 fs1 fc1 sc0 ls0 ws0">below. </div>
								<div class="t m0 x4  y1f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2  y20 ff1 fs1 fc2 sc0 ls0 ws0">You provide your Personal Data to us
								</div>
								<div class="t m0 x2  y21 ff2 fs1 fc2 sc0 ls0 ws0">We collect your Personal Data when you
									voluntarily<span class="_ _1"></span> provide it to us. For example, you may </div>
								<div class="t m0 x2  y22 ff2 fs1 fc2 sc0 ls0 ws0">provide your Personal Data to us when
									you:
								</div>
								<div class="t m0 x3  y23 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Complete a user profile or registration forms
											(su<span class="_ _1"></span>ch as your name, contact </span></span></div>
								<div class="t m0 x4  y24 ff2 fs1 fc2 sc0 ls0 ws0">information and other identification
									information where needed); </div>
								<div class="t m0 x4  y25 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
							</div><a class="l" href="https://www.grab.com/privacy">
								<div class="d m1"
									style="border-style:none;position:absolute;left:262.420000px;bottom:711.860000px;width:70.570000px;height:15.810000px;background-color:rgba(255,255,255,0.000001);">
								</div>
							</a><a class="l" href="https://www.grab.com/terms/driver">
								<div class="d m1"
									style="border-style:none;position:absolute;left:444.190000px;bottom:711.860000px;width:81.360000px;height:15.810000px;background-color:rgba(255,255,255,0.000001);">
								</div>
							</a><a class="l" href="https://www.grab.com/terms/driver">
								<div class="d m1"
									style="border-style:none;position:absolute;left:69.750000px;bottom:688.050000px;width:56.690000px;height:23.810000px;background-color:rgba(255,255,255,0.000001);">
								</div>
							</a>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf2" class="pf w0 h0" data-page-no="2">
						<div class="pc pc2 w0 h0"><img class="bi x0 y26 w1 h7" alt="" src="bg2.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y27 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Provide information to assess your eligibility to
											provide services as a <span class="_ _0"></span>gogo20 driver </span></span>
								</div>
								<div class="t m0 x4  y28 ff2 fs1 fc2 sc0 ls0 ws0">partner or <span class="ff3">delivery
										partner (such as your driver’s lic<span class="_ _1"></span>ense information,
										vehicle </span></div>
								<div class="t m0 x4  y29 ff2 fs1 fc2 sc0 ls0 ws0">information and background check
									results
									(as legally permissible)); </div>
								<div class="t m0 x4  y2a ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y2b ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Interact with our social media pages (such as your
											<span class="_ _0"></span>social media account ID, profile </span></span>
								</div>
								<div class="t m0 x4  y2c ff2 fs1 fc2 sc0 ls0 ws0">photo and any other publicly available
									data); </div>
								<div class="t m0 x4  y2d ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y2e ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Participate in contests or events organised by us
											(<span class="_ _1"></span>such as the<span class="_ _0"></span> pictures,
											audio
											files, or </span></span></div>
								<div class="t m0 x4  y2f ff2 fs1 fc2 sc0 ls0 ws0">videos you may submit, which may
									include
									images of yourself); </div>
								<div class="t m0 x4  y30 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y31 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Verify your identity through various means (such as
											social media<span class="_ _1"></span> logins, submission </span></span>
								</div>
								<div class="t m0 x4  y32 ff2 fs1 fc2 sc0 ls3 ws0">of<span class="ls0"> selfie images or
										independently verified payme<span class="_ _1"></span>nt card information);
									</span>
								</div>
								<div class="t m0 x4  y33 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y34 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Fill up demographic information in surveys (such as
											your age, gender, and other </span></span></div>
								<div class="t m0 x4  y35 ff2 fs1 fc2 sc0 ls0 ws0">information you may volunteer such as
									your
									mari<span class="_ _1"></span>tal status, occupation and income </div>
								<div class="t m0 x4  y36 ff2 fs1 fc2 sc0 ls0 ws0">information); and </div>
								<div class="t m0 x4  y37 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y38 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Agree to take a ride with in-vehicle audio and/or
											video
											recording features. </span></span></div>
								<div class="t m0 x2  y39 ff2 fs1 fc2 sc0 ls0 ws0">In certain circumstances, you may need
									to
									provide your Personal Data i<span class="_ _1"></span>n order to comply </div>
								<div class="t m0 x2  y3a ff2 fs1 fc2 sc0 ls0 ws0">with legal requirements or contractual
									obligations, or where it is necessar<span class="_ _1"></span>y to conclude a </div>
								<div class="t m0 x2  y3b ff2 fs1 fc2 sc0 ls0 ws0">contract. Failure to provide such
									Personal
									Data, under such circumstance, <span class="_ _1"></span>may constitute<span
										class="_ _0"></span> </div>
								<div class="t m0 x2  y3c ff2 fs1 fc2 sc0 ls0 ws0">failure to comply with legal
									requirements
									or contractual obligations, or in<span class="_ _1"></span>ability to conclude
								</div>
								<div class="t m0 x2  y3d ff2 fs1 fc2 sc0 ls0 ws0">a contract with you, as the case may
									be.
								</div>
								<div class="t m0 x2  y3e ff1 fs1 fc2 sc0 ls0 ws0">When our services are used </div>
								<div class="t m0 x2  y3f ff2 fs1 fc2 sc0 ls0 ws0">Personal Data may be collected through
									the
									normal operation of our Apps,<span class="_ _1"></span> Websites and </div>
								<div class="t m0 x2  y40 ff2 fs1 fc2 sc0 ls0 ws0">Services. Some examples are: </div>
								<div class="t m0 x3  y41 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Your location (to detect pick-up locations and
											abnormal
											route variations); </span></span></div>
								<div class="t m0 x4  y42 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y43 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Feedback, Ratings and Compliments; </span></span>
								</div>
								<div class="t m0 x4  y44 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y45 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Transaction information (such as payment method and
											distance travelled); </span></span></div>
								<div class="t m0 x4  y46 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y47 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Information about how you interacted with our Apps,
											Website or Services (such as </span></span></div>
								<div class="t m0 x4  y48 ff2 fs1 fc2 sc0 ls0 ws0">features used and content viewed);
								</div>
								<div class="t m0 x4  y49 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y4a ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Device information (such as hardware model and
											serial
											number, IP address, file </span></span></div>
								<div class="t m0 x4  y4b ff2 fs1 fc2 sc0 ls0 ws0">names and versions and advertising
									identifiers or any information that may provide </div>
								<div class="t m0 x4  y4c ff2 fs1 fc2 sc0 ls0 ws0">indication of device or app
									modification);
								</div>
								<div class="t m0 x4  y4d ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y4e ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Personal data you enter in messages when you use
											our
											in-app communication </span></span></div>
								<div class="t m0 x4  y4f ff2 fs1 fc2 sc0 ls0 ws0">features; and </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf3" class="pf w0 h0" data-page-no="3">
						<div class="pc pc3 w0 h0"><img class="bi x0 y50 w1 h8" alt="" src="bg3.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y27 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Personal data that may be captured through your
											interaction with us, our agents, in-</span></span></div>
								<div class="t m0 x4  y28 ff2 fs1 fc2 sc0 ls0 ws0">vehicle audio and/or video recording
									during a ride (such as<span class="_ _1"></span> your<span class="_ _0"></span>
									image or
									voice or </div>
								<div class="t m0 x4  y29 ff2 fs1 fc2 sc0 ls0 ws0">both, and its related metadata).
								</div>
								<div class="t m0 x2  y51 ff1 fs1 fc2 sc0 ls0 ws0">From other sources </div>
								<div class="t m0 x2  y52 ff2 fs1 fc2 sc0 ls0 ws0">When we collect Personal Data,
									including
									but not limited to your name, contact information </div>
								<div class="t m0 x2  y53 ff2 fs1 fc2 sc0 ls0 ws0">and other identification information
									where
									needed from other sources, we make sure that </div>
								<div class="t m0 x2  y54 ff2 fs1 fc2 sc0 ls0 ws0">that data is transferred to us in
									accordance with applicable laws. Such sources include:<span class="_ _0"></span>
								</div>
								<div class="t m0 x3  y55 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Referral programmes; </span></span></div>
								<div class="t m0 x4  y56 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y57 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Our business partners, such as fleet partners,
											payment
											provi<span class="_ _1"></span>ders, ride<span class="_ _0"></span>-hailing
										</span></span></div>
								<div class="t m0 x4  y58 ff2 fs1 fc2 sc0 ls0 ws0">partners and transport partners;
								</div>
								<div class="t m0 x4  y59 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y5a ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Insurance and financial providers; </span></span>
								</div>
								<div class="t m0 x4  y5b ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y5c ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Credit bureaus and other credit reporting
											agencie<span class="_ _1"></span>s;<span class="_ _0"></span> </span></span>
								</div>
								<div class="t m0 x4  y5d ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y5e ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Publicly available sources of data; </span></span>
								</div>
								<div class="t m0 x4  y5f ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y60 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Governmental sources of data; </span></span></div>
								<div class="t m0 x4  y61 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y62 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">When our users add you as an emergency contact; and
										</span></span></div>
								<div class="t m0 x4  y63 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y64 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Marketing services providers or partners.
										</span></span></div>
								<div class="t m0 x2  y65 ff1 fs1 fc2 sc0 ls0 ws0">Personal Data about driver partners
								</div>
								<div class="t m0 x2  y66 ff2 fs1 fc2 sc0 ls0 ws0">If you are a driver partner, we may
									collect: </div>
								<div class="t m0 x3  y67 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Telematics data (such as your speed, acceleration,
											and
											braking data); </span></span></div>
								<div class="t m0 x4  y68 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y69 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Device data (such as accelerometer data, GPS
											location,
											your IMEI num<span class="_ _1"></span>be<span class="_ _0"></span>r and the
										</span></span></div>
								<div class="t m0 x4  y6a ff2 fs1 fc2 sc0 ls0 ws0">names of apps you have installed on
									your
									device); </div>
								<div class="t m0 x4  y6b ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y6c ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Your vehicle registration data; and </span></span>
								</div>
								<div class="t m0 x4  y6d ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y6e ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Personal data that may be captured through your
											interaction with us, our agents, </span></span></div>
								<div class="t m0 x4  y6f ff2 fs1 fc2 sc0 ls0 ws0">our in-vehicle audio and/or video
									recording during a ride (such as your <span class="_ _0"></span>image or </div>
								<div class="t m0 x4  y70 ff2 fs1 fc2 sc0 ls0 ws0">voice or both, and its related
									metadata).
								</div>
								<div class="t m0 x2  y71 ff1 fs1 fc2 sc0 ls0 ws0">Sensitive Personal Data </div>
								<div class="t m0 x2  y72 ff2 fs1 fc2 sc0 ls0 ws0">Some of the Personal Data that we
									collect
									is sensi<span class="_ _1"></span>tive in nature. This includes Personal Data </div>
								<div class="t m0 x2  y73 ff2 fs1 fc2 sc0 ls0 ws0">pertaining to your race, national ID
									information, <span class="_ _1"></span>religious beliefs, background information
								</div>
								<div class="t m0 x2  y74 ff2 fs1 fc2 sc0 ls0 ws0">(including financial and criminal
									records,
									where<span class="_ _1"></span> legally p<span class="_ _0"></span>ermissible),
									health
									data, disabili<span class="_ _1"></span>ty, </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf4" class="pf w0 h0" data-page-no="4">
						<div class="pc pc4 w0 h0"><img class="bi x0 y50 w1 h8" alt="" src="bg4.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2  y27 ff2 fs1 fc2 sc0 ls0 ws0">marital status and biometric data, as
									applicable. <span class="_ _1"></span>We collect this information<span
										class="_ _0"></span> only with your </div>
								<div class="t m0 x2  y28 ff2 fs1 fc2 sc0 ls0 ws0">consent and/or in strict compliance
									with
									applicable laws. </div>
								<div class="t m0 x2  y75 ff1 fs1 fc2 sc0 ls3 ws0">In<span class="ls0">-vehicle recording
									</span></div>
								<div class="t m0 x2  y76 ff1 fs1 fc2 sc0 ls0 ws0">gogo20<span class="ff4">’s
										applications or
										devices</span> </div>
								<div class="t m0 x2  y77 ff2 fs1 fc2 sc0 ls0 ws0">gogo20 may install in-vehicle audio
									and/or
									video recording applications or devices <span class="_ _1"></span>to </div>
								<div class="t m0 x2  y78 ff2 fs1 fc2 sc0 ls0 ws0">promote the safety and security of
									gogo20
									driver partners, delivery partners and </div>
								<div class="t m0 x2  y79 ff2 fs1 fc2 sc0 ls0 ws0">passengers. Your Personal Data may be
									captured in these audio and/or video </div>
								<div class="t m0 x2  y7a ff2 fs1 fc2 sc0 ls0 ws0">recordings. Where in-vehicle audio
									and/or
									video recordings are made, such recordings are </div>
								<div class="t m0 x2  y7b ff2 fs1 fc2 sc0 ls0 ws0">collected, processed, used and stored
									in a
									manner that is<span class="_ _1"></span> compliant with applicable laws.<span
										class="_ _0"></span> </div>
								<div class="t m0 x2  y7c ff1 fs1 fc2 sc0 ls0 ws0">Personal In-vehicle cameras<span
										class="ff2"> </span></div>
								<div class="t m0 x2  y7d ff2 fs1 fc2 sc0 ls0 ws0">Some gogo20 partners may install
									personal
									in-vehicle cameras in their vehicles for their own </div>
								<div class="t m0 x2  y7e ff2 fs1 fc2 sc0 ls0 ws0">purposes (including safety and
									security).
									The use of such in-<span class="_ _0"></span>vehicle cameras is not endorsed </div>
								<div class="t m0 x2  y7f ff2 fs1 fc2 sc0 ls0 ws0">or prohibited by gogo20. The
									collection,
									use and disclosure of Personal Data obtained from </div>
								<div class="t m0 x2  y80 ff2 fs1 fc2 sc0 ls0 ws0">personal in-vehicle cameras is the
									responsibility of the relevant partner. Please check with </div>
								<div class="t m0 x2  y81 ff2 fs1 fc2 sc0 ls0 ws0">the relevant partner if you have any
									queries abo<span class="_ _1"></span>ut their use of personal in<span
										class="_ _0"></span>-vehicle cameras. </div>
								<div class="t m0 x2  y82 ff1 fs1 fc2 sc0 ls0 ws0">Telematics devices<span class="ff2">
									</span></div>
								<div class="t m0 x2  y83 ff2 fs1 fc2 sc0 ls0 ws0">Gogo20 works with some partners to
									install
									telematics devices in selected rental vehicles for </div>
								<div class="t m0 x2  y84 ff2 fs1 fc2 sc0 ls0 ws0">the following purposes: </div>
								<div class="t m0 x3  y85 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">To ensure that the vehicle is maintained
											appropri<span class="_ _1"></span>ately and serviced in a timely
										</span></span></div>
								<div class="t m0 x4  y86 ff2 fs1 fc2 sc0 ls0 ws0">fashion; </div>
								<div class="t m0 x4  y87 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y88 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">To help maintain the safety, security and integrity
											of
											our products and services; </span></span></div>
								<div class="t m0 x4  y89 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y8a ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">To improve and enhance our products and services;
											and
										</span></span></div>
								<div class="t m0 x4  y8b ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y8c ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">For internal tracking of the vehicle, analysis and
											adminis<span class="_ _1"></span>trative purposes.<span class="_ _0"></span>
										</span></span></div>
								<div class="t m0 x2  y8d ff2 fs1 fc2 sc0 ls0 ws0">If you are a driver partner, these
									devices
									will collect telematics data (such as your <span class="_ _0"></span>speed, </div>
								<div class="t m0 x2  y8e ff2 fs1 fc2 sc0 ls0 ws0">acceleration, and braking data) and
									your
									location information. If you are a passenger </div>
								<div class="t m0 x2  y8f ff2 fs1 fc2 sc0 ls0 ws0">onboard one of our vehicles fitted
									with
									these dev<span class="_ _1"></span>ices, your location data (i.e. the position of
								</div>
								<div class="t m0 x2  y90 ff2 fs1 fc2 sc0 ls0 ws0">the car) will be incidentally
									collected as
									well. </div>
								<div class="t m0 x2  y91 ff2 fs1 fc2 sc0 ls0 ws0">The data collected by these devices
									are
									owned by these partners who have entered into </div>
								<div class="t m0 x2  y92 ff2 fs1 fc2 sc0 ls0 ws0">appropriate contractual undertakings
									with
									gogo20 to safeguard this data. While these </div>
								<div class="t m0 x2  y93 ff2 fs1 fc2 sc0 ls0 ws0">partners share such telematics data
									with
									us (to enable us to fulfil the <span class="_ _1"></span>purposes stated </div>
								<div class="t m0 x2  y94 ff2 fs1 fc2 sc0 ls0 ws0">above), we do not share any personally
									identifying information about our driver partners or </div>
								<div class="t m0 x2  y95 ff2 fs1 fc2 sc0 ls0 ws0">passengers with these partners. </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf5" class="pf w0 h0" data-page-no="5">
						<div class="pc pc5 w0 h0"><img class="bi x0 y96 w1 h9" alt="" src="bg5.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2  y27 ff1 fs1 fc2 sc0 ls0 ws0">Personal Data of minors<span
										class="ff2">
									</span></div>
								<div class="t m0 x2  y97 ff2 fs1 fc2 sc0 ls0 ws0">As a parent or legal guardian, please
									do
									not allow minors under your<span class="_ _1"></span> care to submit </div>
								<div class="t m0 x2  y75 ff2 fs1 fc2 sc0 ls0 ws0">Personal Data to gogo20. In the event
									that
									such Personal Data of a minor is disclosed to </div>
								<div class="t m0 x2  y98 ff2 fs1 fc2 sc0 ls0 ws0">gogo20<span class="ff3">, you hereby
										consent to the processing of the minor’s Personal Data and accept and </span>
								</div>
								<div class="t m0 x2  y99 ff2 fs1 fc2 sc0 ls0 ws0">agree to be bound by this Policy and
									take
									responsibility for his or her actions. </div>
								<div class="t m0 x2  y9a ff1 fs1 fc2 sc0 ls0 ws0">When you provide Personal Data of
									other
									individuals to us </div>
								<div class="t m0 x2  y79 ff2 fs1 fc2 sc0 ls0 ws0">In some situations, you may provide
									Personal Data of other individuals (s<span class="_ _1"></span>uch as your spouse,
								</div>
								<div class="t m0 x2  y7a ff2 fs1 fc2 sc0 ls0 ws0">family members or friends) to us. For
									example, you may add them<span class="_ _1"></span> as your emergency </div>
								<div class="t m0 x2  y7b ff2 fs1 fc2 sc0 ls0 ws0">contact. If you provide us with their
									Personal Data, you represent and warrant that you<span class="_ _0"></span> have
								</div>
								<div class="t m0 x2  y9b ff2 fs1 fc2 sc0 ls0 ws0">obtained their consent for their
									Personal
									Data to be collected, used and di<span class="_ _1"></span>sclosed as set out </div>
								<div class="t m0 x2  y9c ff2 fs1 fc2 sc0 ls0 ws0">in this Policy. </div>
								<div class="t m0 x3 h5 y9d ff1 fs1 fc1 sc0 ls3 ws0">2.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Use of Person Data </span></span>
								</div>
								<div class="t m0 x2  y9e ff2 fs1 fc2 sc0 ls0 ws0">gogo20 may use, combine and process
									your
									Personal Data for the followi<span class="_ _1"></span>ng purposes </div>
								<div class="t m0 x2  y9f ff3 fs1 fc2 sc0 ls2 ws0">(“<span class="ff1 ls0">Purposes<span
											class="ff3">”):<span class="ff2"> </span></span></span></div>
								<div class="t m0 x2  ya0 ff1 fs1 fc2 sc0 ls0 ws0">Providing services and features<span
										class="ff2"> </span></div>
								<div class="t m0 x2  ya1 ff2 fs1 fc2 sc0 ls0 ws0">Your Personal Data will be used to
									provide, personalise, maintain and improve our<span class="_ _0"></span> Apps,
								</div>
								<div class="t m0 x2  ya2 ff2 fs1 fc2 sc0 ls0 ws0">Websites and Services. This includes
									using
									your Personal Data to: </div>
								<div class="t m0 x3  ya3 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Provide you with Services across our various
											busi<span class="_ _1"></span>ness verticals; </span></span></div>
								<div class="t m0 x4  ya4 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  ya5 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Engage you to provide Services; </span></span>
								</div>
								<div class="t m0 x4  ya6 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  ya7 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Create, administer and update your account;
										</span></span></div>
								<div class="t m0 x4  ya8 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  ya9 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Conduct due diligence checks; </span></span></div>
								<div class="t m0 x4  yaa ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yab ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Verify your identity; </span></span></div>
								<div class="t m0 x4  yac ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yad ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Verify your age (where necessary); </span></span>
								</div>
								<div class="t m0 x4  yae ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yaf ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Validate your ride and process payments;
										</span></span>
								</div>
								<div class="t m0 x4  yb0 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yb1 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Offer, obtain, provide, facilitate or maintain
											insurance or financing solutions; </span></span></div>
								<div class="t m0 x4  yb2 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yb3 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Track the progress of your trip and detect abnormal
											trip variations; </span></span></div>
								<div class="t m0 x4  yb4 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yb5 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Enable features that personalise your App, such as
											lists of your favourite places and </span></span></div>
								<div class="t m0 x4  yb6 ff2 fs1 fc2 sc0 ls0 ws0">previous destinations; </div>
								<div class="t m0 x3  yb7 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Make your experience more seamless, such as au<span
												class="_ _1"></span>tomatically filling in you<span
												class="_ _0"></span><span class="ls5">r </span></span></span></div>
								<div class="t m0 x4  yb8 ff2 fs1 fc2 sc0 ls0 ws0">registration information (such as your
									name or phone number) from one Service to </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf6" class="pf w0 h0" data-page-no="6">
						<div class="pc pc6 w0 h0"><img class="bi x0 yb9 w1 ha" alt="" src="bg6.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x4  y27 ff2 fs1 fc2 sc0 ls0 ws0">another Service or when you
									participate in
									our surveys; </div>
								<div class="t m0 x4  y28 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y29 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Perform internal operations necessary to provide
											our
											Services, including </span></span></div>
								<div class="t m0 x4  y2a ff2 fs1 fc2 sc0 ls0 ws0">troubleshooting software bugs and
									operational problems, conducting data analysis, </div>
								<div class="t m0 x4  y2b ff2 fs1 fc2 sc0 ls0 ws0">testing and research, monitoring and
									analysing usage and activi<span class="_ _1"></span>ty trends;<span
										class="_ _0"></span>
								</div>
								<div class="t m0 x4  y2c ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y2d ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Protect the security or integrity of the Services
											and
											any facilities or e<span class="_ _1"></span>quipment used </span></span>
								</div>
								<div class="t m0 x4  y2e ff2 fs1 fc2 sc0 ls0 ws0">to make the Services available; </div>
								<div class="t m0 x4  y2f ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y30 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Process and manage your rewards; </span></span>
								</div>
								<div class="t m0 x4  y31 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y32 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Enable communications between our users;
										</span></span>
								</div>
								<div class="t m0 x4  y33 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y34 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Process, manage or verify your application of
											promotions, re<span class="_ _1"></span>ward<span class="_ _0"></span>s and
											subscriptions </span></span></div>
								<div class="t m0 x4  y35 ff2 fs1 fc2 sc0 ls0 ws0">with gogo20; </div>
								<div class="t m0 x4  y36 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y37 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Enable our partners to manage and allocate fleet
											resources;<span class="_ _1"></span> and </span></span></div>
								<div class="t m0 x4  y38 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yba ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Fulfil the services to you as a data processor,
											where
											you have provided consent to </span></span></div>
								<div class="t m0 x4  ybb ff2 fs1 fc2 sc0 ls4 ws0">the<span class="ls0"> data controller
										(i.e. t<span class="_ _1"></span>he organisation you had purchased goods or
										services
										f<span class="_ _0"></span>rom, </span></div>
								<div class="t m0 x4  ybc ff2 fs1 fc2 sc0 ls0 ws0">and for whom gogo20 is providing
									services
									on behalf of) for such services to be </div>
								<div class="t m0 x4  ybd ff2 fs1 fc2 sc0 ls0 ws0">rendered. <span class="ls6"> </span>
								</div>
								<div class="t m0 x2  y3d ff1 fs1 fc2 sc0 ls0 ws0">Safety and security<span class="ff2">
									</span></div>
								<div class="t m0 x2  y3e ff2 fs1 fc2 sc0 ls0 ws0">We use your data to ensure the safety
									and
									security of our Services and all users. This </div>
								<div class="t m0 x2  ybe ff2 fs1 fc2 sc0 ls0 ws0">includes: </div>
								<div class="t m0 x3  y40 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Screening driver and delivery partners before
											enabling
											their use of our Ser<span class="_ _1"></span>vices; </span></span></div>
								<div class="t m0 x4  ybf ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yc0 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Identifying unsafe driving behaviour such as
											spee<span class="_ _1"></span>ding, harsh braking and </span></span></div>
								<div class="t m0 x4  yc1 ff2 fs1 fc2 sc0 ls0 ws0">acceleration, and providing
									personalised
									feedback<span class="_ _1"></span> to driver partners;<span class="_ _0"></span>
								</div>
								<div class="t m0 x4  yc2 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yc3 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Verifying your identity when you log in to gogo20;
										</span></span></div>
								<div class="t m0 x4  yc4 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yc5 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Using device, location, profile, usage and other
											Personal Data to prevent, d<span class="_ _1"></span>etect and
										</span></span>
								</div>
								<div class="t m0 x4  yc6 ff2 fs1 fc2 sc0 ls0 ws0">combat fraud or unsafe activities;
								</div>
								<div class="t m0 x4  yc7 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yc8 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Sharing drivers and passeng<span class="ff3">ers’
												location and details when the emergency </span>button or </span></span>
								</div>
								<div class="t m0 x4  yc9 ff3 fs1 fc2 sc0 ls0 ws0">the “<span class="ff2">VAS
										Features</span>” feature is activated;<span class="ff2"> </span></div>
								<div class="t m0 x4  yca ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  ycb ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">M<span class="ff3">onitoring compliance with our
												terms
												and cond<span class="_ _1"></span>itions, policies and Driver’s Code of
											</span></span></span></div>
								<div class="t m0 x4  ycc ff2 fs1 fc2 sc0 ls0 ws0">Conduct; and </div>
								<div class="t m0 x4  ycd ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yce ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Detecting, preventing and prosecuting crime.
										</span></span></div>
								<div class="t m0 x2  ycf ff1 fs1 fc2 sc0 ls0 ws0">Customer support<span class="ff2">
									</span>
								</div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf7" class="pf w0 h0" data-page-no="7">
						<div class="pc pc7 w0 h0"><img class="bi x0 yd0 w1 hb" alt="" src="bg7.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2  y27 ff2 fs1 fc2 sc0 ls0 ws0">We use Personal Data to resolve
									customer
									support issues. For example, we may: </div>
								<div class="t m0 x3  y97 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Investigate and address concerns; </span></span>
								</div>
								<div class="t m0 x4  y75 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y98 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Monitor and improve our customer support responses;
										</span></span></div>
								<div class="t m0 x4  y99 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yd1 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Respond to questions, comments and feedback; and
										</span></span></div>
								<div class="t m0 x4  yd2 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yd3 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Inform you about steps taken to resolve customer
											support issues. </span></span></div>
								<div class="t m0 x2  yd4 ff1 fs1 fc2 sc0 ls0 ws0">Research and development and
									security<span class="ff2"> </span></div>
								<div class="t m0 x2  y57 ff2 fs1 fc2 sc0 ls0 ws0">We may use the Personal Data we
									collect
									for testing, research, a<span class="_ _1"></span>nalysis and product </div>
								<div class="t m0 x2  y58 ff2 fs1 fc2 sc0 ls0 ws0">development. This allows us to
									understand
									and analyse your needs and preferences, protect </div>
								<div class="t m0 x2  y59 ff2 fs1 fc2 sc0 ls0 ws0">your Personal Data, improve and
									enhance
									the safety and security of our Services, develop </div>
								<div class="t m0 x2  y5a ff2 fs1 fc2 sc0 ls0 ws0">new features, products and services,
									and
									facilitate insurance and finance <span class="_ _1"></span>solutions.<span
										class="_ _0"></span> </div>
								<div class="t m0 x2  yd5 ff1 fs1 fc2 sc0 ls0 ws0">Legal purposes<span class="ff2">
									</span>
								</div>
								<div class="t m0 x2  yd6 ff2 fs1 fc2 sc0 ls0 ws0">We may use the Personal Data we
									collect to
									inves<span class="_ _1"></span>tigate and resolve claims or disputes,<span
										class="_ _0"></span> or as </div>
								<div class="t m0 x2  yd7 ff2 fs1 fc2 sc0 ls0 ws0">allowed or required by applicable law.
								</div>
								<div class="t m0 x2  yd8 ff2 fs1 fc2 sc0 ls0 ws0">We may also use your Personal Data
									when we
									are required, advised, reco<span class="_ _1"></span>mmended, </div>
								<div class="t m0 x2  yd9 ff2 fs1 fc2 sc0 ls0 ws0">expected or requested to do so by our
									legal advisors or any local or foreig<span class="_ _1"></span>n legal, regulatory,
								</div>
								<div class="t m0 x2  yda ff2 fs1 fc2 sc0 ls0 ws0">governmental or other authority.
								</div>
								<div class="t m0 x2  ydb ff2 fs1 fc2 sc0 ls7 ws0">Fo<span class="ls0">r example, we may
										use
										your Personal Data to: </span></div>
								<div class="t m0 x3  ydc ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Comply with court orders or other legal,
											governmental
											or<span class="_ _1"></span> regulatory requirements; </span></span></div>
								<div class="t m0 x4  ydd ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yde ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Enforce our Terms of Service or other
											agreements<span class="_ _1"></span>; and </span></span></div>
								<div class="t m0 x4  ydf ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  ye0 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Protect our rights or property in the event of a
											claim
											or dispute. </span></span></div>
								<div class="t m0 x2  ye1 ff2 fs1 fc2 sc0 ls0 ws0">We may also use your Personal Data in
									connection with mergers, acquisitions, joint </div>
								<div class="t m0 x2  ye2 ff2 fs1 fc2 sc0 ls0 ws0">ventures, sale of company assets,
									consolidation, restructuring, financing, business asset </div>
								<div class="t m0 x2  ye3 ff2 fs1 fc2 sc0 ls0 ws0">transactions, or acquisition of all or
									part of our b<span class="_ _1"></span>usiness by another company.<span
										class="_ _0"></span> </div>
								<div class="t m0 x2  ye4 ff1 fs1 fc2 sc0 ls0 ws0">Marketing and promotions<span
										class="ff2">
									</span></div>
								<div class="t m0 x2  ye5 ff2 fs1 fc2 sc0 ls0 ws0">We may use your Personal Data to
									market
									gogo20 and <span class="ls8">it<span class="_ _0"></span></span><span class="ff3">’s
										partners’, sponsors’ and </span></div>
								<div class="t m0 x2  ye6 ff3 fs1 fc2 sc0 ls0 ws0">advertisers’ products, services,
									events or
									promotions. For example, we may:<span class="ff2"> </span></div>
								<div class="t m0 x3  ye7 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Send you alerts, newsletters, updates, mailers,
											promotional materials, sp<span class="_ _1"></span>ecial </span></span>
								</div>
								<div class="t m0 x4  ye8 ff2 fs1 fc2 sc0 ls0 ws0">privileges, festive greetings; and
								</div>
								<div class="t m0 x4  ye9 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf8" class="pf w0 h0" data-page-no="8">
						<div class="pc pc8 w0 h0"><img class="bi x0 yea w1 hc" alt="" src="bg8.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y27 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Notify, invite and manage your participation in our
											events<span class="_ _1"></span> or activities. </span></span></div>
								<div class="t m0 x2  yeb ff2 fs1 fc2 sc0 ls0 ws0">We may communicate such marketing to
									you
									by post, telephone call, s<span class="_ _1"></span>hort message service, </div>
								<div class="t m0 x2  yec ff2 fs1 fc2 sc0 ls0 ws0">online messaging service, push
									notification by hand and by email. </div>
								<div class="t m0 x2  yed ff2 fs1 fc2 sc0 ls0 ws0">If you wish to unsubscribe to the
									processing of your Personal Data for mar<span class="_ _1"></span>keting and </div>
								<div class="t m0 x2  y52 ff2 fs1 fc2 sc0 ls0 ws0">promotions, please click on the
									unsubscribe link i<span class="_ _1"></span>n the relevant email or message. </div>
								<div class="t m0 x2  y53 ff2 fs1 fc2 sc0 ls0 ws0">Alternatively, you may also update
									your
									preferences in our App settings. </div>
								<div class="t m0 x3 h5 yee ff1 fs1 fc1 sc0 ls3 ws0">3.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Disclosure of Personal
											Da</span></span>ta<span class="ls0"> </span></div>
								<div class="t m0 x2  yef ff2 fs1 fc2 sc0 ls0 ws0">We need to share Personal Data with
									various parties <span class="_ _1"></span>for the Purposes. These parties </div>
								<div class="t m0 x2  yf0 ff2 fs1 fc2 sc0 ls0 ws0">include: </div>
								<div class="t m0 x2  yf1 ff1 fs1 fc2 sc0 ls0 ws0">Other users<span class="ff2"> </span>
								</div>
								<div class="t m0 x2  yf2 ff2 fs1 fc2 sc0 ls0 ws0">For example: </div>
								<div class="t m0 x3  yf3 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1 ls9">If<span class="ls0"> you are a passenger, we
												may
												share your pick-up and drop<span class="_ _0"></span>-off locations with
												our
											</span></span></span></div>
								<div class="t m0 x4  yf4 ff2 fs1 fc2 sc0 ls0 ws0">driver partner fulfilling your
									servic<span class="ls1">e </span>request. </div>
								<div class="t m0 x4  yf5 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yf6 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">If you are a driver partner, we may share your
											Personal
											Data with your passenger </span></span></div>
								<div class="t m0 x4  yf7 ff2 fs1 fc2 sc0 ls0 ws0">including your name and photo; your
									vehicle make, model, number pla<span class="_ _1"></span>te, location </div>
								<div class="t m0 x4  yf8 ff2 fs1 fc2 sc0 ls0 ws0">and average rating. </div>
								<div class="t m0 x4  yf9 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yfa ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">If you are a delivery partner, we may share your
											Personal Data with your selected </span></span></div>
								<div class="t m0 x4  yfb ff2 fs1 fc2 sc0 ls0 ws0">vendor/merchant and user, including
									your
									name and photo; your vehicle <span class="_ _1"></span>make,<span
										class="_ _0"></span>
								</div>
								<div class="t m0 x4  yfc ff2 fs1 fc2 sc0 ls0 ws0">model, location and average rating.
								</div>
								<div class="t m0 x4  yfd ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  yfe ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">If you are using our gogo20 other services, we may
											share your Personal Data with </span></span></div>
								<div class="t m0 x4  yff ff2 fs1 fc2 sc0 ls0 ws0">the recipient of your parcel, and vice
									ver<span class="lsa">sa</span>, as well as the delivery partner in charge </div>
								<div class="t m0 x4  y100 ff2 fs1 fc2 sc0 ls0 ws0">of fulfilling your service request.
								</div>
								<div class="t m0 x4  y101 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y102 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">If you use our in-app chat service, we may share
											your
											mobile number and gogo20<span class="_ _0"></span>-</span></span></div>
								<div class="t m0 x4  y103 ff2 fs1 fc2 sc0 ls0 ws0">registered name with the other
									parties to
									your chat.<span class="ls6"> </span> </div>
								<div class="t m0 x2  y104 ff1 fs1 fc2 sc0 ls0 ws0">With third parties<span class="ff2">
									</span></div>
								<div class="t m0 x2  y105 ff2 fs1 fc2 sc0 ls0 ws0">For example, we may <span
										class="lsa">sh<span class="ls7">are</span></span> <span class="ff3">a vehicle’s
										location</span> <span class="ff3">and driver’s and/or passenger’s</span> name
									with
								</div>
								<div class="t m0 x2  y106 ff3 fs1 fc2 sc0 ls0 ws0">third parties when a passenger uses
									the
									“Share My Ride” <span class="_ _1"></span>feature or activates the Emergency </div>
								<div class="t m0 x2  y107 ff2 fs1 fc2 sc0 ls0 ws0">Button. </div>
								<div class="t m0 x2  y108 ff1 fs1 fc2 sc0 ls0 ws0">With gogo20 partners at your
									request<span class="ff2"> </span></div>
								<div class="t m0 x2  y109 ff2 fs1 fc2 sc0 ls0 ws0">For example, if you requested a
									service
									through a gogo20 partner or used a promotion </div>
								<div class="t m0 x2  y10a ff2 fs1 fc2 sc0 ls0 ws0">provided by a gogo20 partner, gogo20
									may
									share your Personal Data with that gogo20 </div>
								<div class="t m0 x2  y10b ff2 fs1 fc2 sc0 ls0 ws0">partners. Our partners include
									partners
									that integ<span class="_ _1"></span>rate with our App or our App integrates </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf9" class="pf w0 h0" data-page-no="9">
						<div class="pc pc9 w0 h0"><img class="bi x0 y10c w1 hd" alt="" src="bg9.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2  y27 ff2 fs1 fc2 sc0 ls0 ws0">with, vehicle services partners, or
									business partners which gogo20 collaborates with to </div>
								<div class="t m0 x2  y28 ff2 fs1 fc2 sc0 ls0 ws0">deliver a promotion, competition or
									other
									speci<span class="_ _1"></span>alised service.<span class="_ _0"></span> </div>
								<div class="t m0 x2  y75 ff1 fs1 fc2 sc0 ls0 ws0">With the owner of gogo20 accounts that
									you
									may use<span class="ff2"> </span></div>
								<div class="t m0 x2  y76 ff3 fs1 fc2 sc0 ls0 ws0">For example, your employer may receive
									trip data when you use your empl<span class="_ _1"></span>oyer’s <span
										class="_ _0"></span><span class="ff2">gogo20 </span></div>
								<div class="t m0 x2  y10d ff2 fs1 fc2 sc0 ls0 ws0">for Business accou<span
										class="ls4">nt.</span> </div>
								<div class="t m0 x2  y78 ff1 fs1 fc2 sc0 ls0 ws0">With subsidiaries and affiliates<span
										class="ff2"> </span></div>
								<div class="t m0 x2  y10e ff2 fs1 fc2 sc0 ls0 ws0">We share Personal Data with our
									subsidiaries, associated companies, jointl<span class="_ _1"></span>y con<span
										class="_ _0"></span>trolled </div>
								<div class="t m0 x2  y10f ff2 fs1 fc2 sc0 ls0 ws0">entities and affiliates. </div>
								<div class="t m0 x2  y110 ff1 fs1 fc2 sc0 ls0 ws0">With gogo20<span class="ff4">’s
										service
										providers and business partners<span class="ff2"> </span></span></div>
								<div class="t m0 x2  y111 ff2 fs1 fc2 sc0 ls0 ws0">We may provide Personal Data to our
									vendors, consultants, marketing partners, research </div>
								<div class="t m0 x2  y112 ff2 fs1 fc2 sc0 ls0 ws0">firms, and other service providers or
									business partners. This inclu<span class="_ _1"></span>des:<span
										class="_ _0"></span>
								</div>
								<div class="t m0 x3  y113 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Payment processors and facilitators; </span></span>
								</div>
								<div class="t m0 x4  y114 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y115 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Debt collectors; </span></span></div>
								<div class="t m0 x4  y116 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y117 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Credit bureaus and other credit reporting
											agencie<span class="_ _1"></span>s;<span class="_ _0"></span> </span></span>
								</div>
								<div class="t m0 x4  y83 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y84 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Background check and anti-money laundering service
											providers; </span></span></div>
								<div class="t m0 x4  y118 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y119 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1 ls2">Cl<span class="ls0">oud storage providers;
											</span></span></span></div>
								<div class="t m0 x4  y11a ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y11b ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Marketing partners and marketing platform
											provi<span class="_ _1"></span>ders; </span></span></div>
								<div class="t m0 x4  y11c ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y11d ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Data analytics providers; </span></span></div>
								<div class="t m0 x4  y11e ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y11f ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Research partners, including those performing
											surveys
											or research <span class="_ _1"></span>projects in </span></span></div>
								<div class="t m0 x4  y120 ff2 fs1 fc2 sc0 ls0 ws0">partnership with gogo20 or on
									gogo20<span class="ff3">’s behalf;</span> </div>
								<div class="t m0 x4  y121 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y122 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Fleet and merchant partners; </span></span></div>
								<div class="t m0 x4  y123 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y124 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Insurance and financing partners; </span></span>
								</div>
								<div class="t m0 x4  y125 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y126 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Third party intermediaries involved in the managed
											investment of funds, such as </span></span></div>
								<div class="t m0 x4  y127 ff2 fs1 fc2 sc0 ls0 ws0">brokers, asset managers, and
									custodians;
								</div>
								<div class="t m0 x4  y128 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y129 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Service providers who perform identity verification
											services; and </span></span></div>
								<div class="t m0 x4  y12a ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y12b ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">V<span class="ls1">eh</span>icle solutions
											partners,
											vendors or third-party vehicle suppliers. </span></span></div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pfa" class="pf w0 h0" data-page-no="a">
						<div class="pc pca w0 h0"><img class="bi x0 y12c w1 he" alt="" src="bga.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2  y27 ff1 fs1 fc2 sc0 ls0 ws0">With our legal advisors and
									governmental
									authorities<span class="ff2"> </span></div>
								<div class="t m0 x2  y97 ff2 fs1 fc2 sc0 ls0 ws0">We may share your Personal Data with
									our
									legal <span class="_ _1"></span>advisors, law enforcement officials, </div>
								<div class="t m0 x2  y75 ff2 fs1 fc2 sc0 ls0 ws0">government authorities and other third
									parties. This may take place to fulfil the legal </div>
								<div class="t m0 x2  y98 ff2 fs1 fc2 sc0 ls0 ws0">purposes (mentioned above), or any of
									the
									following circumstances: </div>
								<div class="t m0 x5 hf y10d ff2 fs1 fc2 sc0 ls9 ws0">I.<span class="ff8 ls0"> <span
											class="_ _4"> </span><span class="ff2">Where it is necessary to respond to
											an
											emergency that threatens the<span class="_ _1"></span> life, health or
										</span></span></div>
								<div class="t m0 x4  y9a ff2 fs1 fc2 sc0 ls0 ws0">safety of a person; or </div>
								<div class="t m0 x4  y12d ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x6 hf y12e ff2 fs1 fc2 sc0 ls9 ws0">II.<span class="ff8 ls0"> <span
											class="_ _4"> </span><span class="ff2">Where it is necessary in the public
											interest (e.g. in a public health crisis, for contact </span></span></div>
								<div class="t m0 x4  y12f ff2 fs1 fc2 sc0 ls0 ws0">tracing purposes and safeguarding our
									community). </div>
								<div class="t m0 x3 h5 y57 ff1 fs1 fc1 sc0 ls3 ws0">4.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Retention of Personal Data
										</span></span>
								</div>
								<div class="t m0 x4 h6 y130 ff6 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y131 ff2 fs1 fc1 sc0 ls0 ws0">We retain your Personal Data for the
									period necessary to fulfil the P<span class="_ _1"></span>urposes outlined </div>
								<div class="t m0 x4  y132 ff2 fs1 fc1 sc0 ls0 ws0">in this Policy unless a longer
									retention
									period is required or allowed by law. Once </div>
								<div class="t m0 x4  y133 ff2 fs1 fc1 sc0 ls0 ws0">your Personal Data is no longer
									necessary
									for the Services or Purposes, or we no </div>
								<div class="t m0 x4  y134 ff2 fs1 fc1 sc0 ls0 ws0">longer have a legal or business
									purpose
									for retaining your Personal Data, we take </div>
								<div class="t m0 x4  y135 ff2 fs1 fc1 sc0 ls0 ws0">steps to erase, destroy, anonymise or
									prevent access or use of such Personal Data </div>
								<div class="t m0 x4  y136 ff2 fs1 fc1 sc0 ls0 ws0">for any purpose other than compliance
									with this Policy, or for p<span class="_ _1"></span>urposes of<span
										class="_ _0"></span>
									safety, </div>
								<div class="t m0 x4  y137 ff2 fs1 fc1 sc0 ls0 ws0">security, fraud prevention and
									detection,
									in accordance with the requir<span class="_ _1"></span>ements of </div>
								<div class="t m0 x4  y138 ff2 fs1 fc1 sc0 ls0 ws0">applicable laws. </div>
								<div class="t m0 x4  y139 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y13a ff1 fs1 fc1 sc0 ls3 ws0">5.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">International Transfers of Personal
											Data
										</span></span></div>
								<div class="t m0 x4  y13b ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y13c ff2 fs1 fc1 sc0 ls3 ws0">Yo<span class="ff3 ls0">ur Personal
										Data
										may be transferred from country, state and ci<span class="_ _1"></span>ty (“Home
									</span></div>
								<div class="t m0 x4  y13d ff3 fs1 fc1 sc0 ls0 ws0">Country”) in which you are prese<span
										class="ff2">nt while using our Services to another country, state </span></div>
								<div class="t m0 x4  y13e ff3 fs1 fc1 sc0 ls0 ws0">and city (“Alternate Country”).<span
										class="ff2"> </span></div>
								<div class="t m0 x4  y13f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y140 ff2 fs1 fc1 sc0 ls0 ws0">When we transfer your Personal Data
									from
									your Home Country to the<span class="_ _0"></span> Alternate </div>
								<div class="t m0 x4  y67 ff2 fs1 fc1 sc0 ls0 ws0">Country, we will comply with our legal
									and
									regula<span class="_ _1"></span>tory obligations in relation to your </div>
								<div class="t m0 x4  y141 ff2 fs1 fc1 sc0 ls0 ws0">Personal Data, including having a
									lawful
									basis for transferring Personal Da<span class="_ _1"></span>ta and </div>
								<div class="t m0 x4  y142 ff2 fs1 fc1 sc0 ls0 ws0">putting appropriate safeguards in
									place
									to ensure an ade<span class="_ _1"></span>quate level of protec<span
										class="_ _0"></span>tion for </div>
								<div class="t m0 x4  y143 ff2 fs1 fc1 sc0 ls0 ws0">the Personal Data. We will also
									ensure
									that the re<span class="_ _1"></span>cipient in Alternate Country is </div>
								<div class="t m0 x4  y144 ff2 fs1 fc1 sc0 ls0 ws0">obliged to protect your Personal Data
									at
									a standard of protection comparable to the </div>
								<div class="t m0 x4  y145 ff2 fs1 fc1 sc0 ls0 ws0">protection under applicable laws.
								</div>
								<div class="t m0 x4  y146 ff2 fs1 fc1 sc0 ls0 ws0">Our lawful basis will be either
									consent
									(i.e. we may ask for your consent to transfer </div>
								<div class="t m0 x4  y147 ff2 fs1 fc1 sc0 ls0 ws0">your Personal Data from your Home
									Country
									to the Alternate Country at the time </div>
								<div class="t m0 x4  y126 ff2 fs1 fc1 sc0 ls0 ws0">you provide your Personal Data) or
									one of
									the safeguards permissible by l<span class="_ _1"></span>aws.<span
										class="_ _0"></span>
								</div>
								<div class="t m0 x4  y148 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y149 ff1 fs1 fc1 sc0 ls3 ws0">6.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Cookies and Advertising on the
											Third-Party Platforms </span></span></div>
								<div class="t m0 x4  y4e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y14a ff2 fs1 fc1 sc0 ls0 ws0">gogo20, and third parties with whom
									we
									partner, may use cookies, web beacons, </div>
								<div class="t m0 x4  y14b ff2 fs1 fc1 sc0 ls0 ws0">tags, scripts, local <span
										class="ff3">shared objects such as HTML5 and Flash (sometimes called “flash
									</span>
								</div>
								<div class="t m0 x4  y14c ff3 fs1 fc1 sc0 ls0 ws0">cookies”), advertising identifiers
									(including mobile identi<span class="_ _1"></span>fiers such as Apple’s IDFA or
								</div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pfb" class="pf w0 h0" data-page-no="b">
						<div class="pc pcb w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x4  y27 ff3 fs1 fc1 sc0 ls0 ws0">Google’s Advertising ID) and similar
									technology (“Cookies”) in connection with your </div>
								<div class="t m0 x4  y14d ff2 fs1 fc1 sc0 ls0 ws0">use of the Websites and Apps. Cookies
									may
									have unique identifiers, and reside, </div>
								<div class="t m0 x4  y14e ff2 fs1 fc1 sc0 ls0 ws0">among other places, on your computer
									or
									mobile device, in emails we sen<span class="_ _1"></span>d to you, </div>
								<div class="t m0 x4  y14f ff2 fs1 fc1 sc0 ls0 ws0">and on our web pages. Cookies may
									transmit Personal Data about you and your use </div>
								<div class="t m0 x4  y150 ff2 fs1 fc1 sc0 ls0 ws0">of the Service, such as your browser
									type, search preferences, IP address, data </div>
								<div class="t m0 x4  y151 ff2 fs1 fc1 sc0 ls0 ws0">relating to advertisements that have
									been
									displayed to you or that you have clic<span class="_ _1"></span>ked </div>
								<div class="t m0 x4  y152 ff2 fs1 fc1 sc0 ls0 ws0">on, and the date and time of your
									use.
									Cookies may be persistent or stored only </div>
								<div class="t m0 x4  y153 ff2 fs1 fc1 sc0 ls0 ws0">during an individual session. </div>
								<div class="t m0 x4  y154 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y155 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 may allow third parties to use
									Cookies on the Websites and Apps to collect </div>
								<div class="t m0 x4  y156 ff2 fs1 fc1 sc0 ls0 ws0">the same type of Personal Data for
									the
									same purposes gogo20 does for itself. Third </div>
								<div class="t m0 x4  y157 ff2 fs1 fc1 sc0 ls0 ws0">parties may be able to associate the
									Personal Dat<span class="_ _1"></span>a they collect with other Personal </div>
								<div class="t m0 x4  y158 ff2 fs1 fc1 sc0 ls0 ws0">Data they have about you from other
									sources. We do not necessarily have access to </div>
								<div class="t m0 x4  y159 ff2 fs1 fc1 sc0 ls0 ws0">or control over the Cookies they use.
								</div>
								<div class="t m0 x4  y15a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y15b ff2 fs1 fc1 sc0 ls0 ws0">Additionally, we may share
									non-personally
									identifiable Personal Data with third </div>
								<div class="t m0 x4  y15c ff2 fs1 fc1 sc0 ls0 ws0">parties, such as location data,
									advertising identifi<span class="_ _1"></span>ers, or a cryptographic ha<span
										class="_ _0"></span>sh of a </div>
								<div class="t m0 x4  y15d ff2 fs1 fc1 sc0 ls0 ws0">common account identifier (such as an
									email address)<span class="_ _1"></span>, to facilitate the display of </div>
								<div class="t m0 x4  y15e ff2 fs1 fc1 sc0 ls0 ws0">targeted advertising on third party
									platforms. </div>
								<div class="t m0 x4  y15f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y160 ff2 fs1 fc1 sc0 ls0 ws0">If you do not wish for your Personal
									Data
									to be collected via Cookies on the </div>
								<div class="t m0 x4  y161 ff2 fs1 fc1 sc0 ls0 ws0">Websites, you may deactivate cookies
									by
									adjusting your internet browser settings to </div>
								<div class="t m0 x4  y162 ff2 fs1 fc1 sc0 ls0 ws0">disable, block or deactivate cookies,
									by
									deleting<span class="_ _1"></span> your browsing history and clearing </div>
								<div class="t m0 x4  y163 ff2 fs1 fc1 sc0 ls0 ws0">the cache from your internet browser.
									You
									may also limit our sharing of some of this </div>
								<div class="t m0 x4  y164 ff2 fs1 fc1 sc0 ls0 ws0">Personal Data through your App
									(Settings
									&gt; Privacy &gt; Ads) and mobile device </div>
								<div class="t m0 x4  y165 ff2 fs1 fc1 sc0 ls0 ws0">settings. </div>
								<div class="t m0 x4  y166 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y167 ff1 fs1 fc1 sc0 ls3 ws0">7.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Protection of Personal Data
										</span></span></div>
								<div class="t m0 x4  y168 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y169 ff2 fs1 fc1 sc0 ls0 ws0">We will take reasonable legal,
									organisational and <span class="_ _1"></span>technical measures to ensure that
								</div>
								<div class="t m0 x4  y16a ff2 fs1 fc1 sc0 ls0 ws0">your Personal Data is protected. This
									includes measures to prevent Personal </div>
								<div class="t m0 x4  y16b ff2 fs1 fc1 sc0 ls0 ws0">Data from getting lost, or used or
									accessed in an unauthorised way. We limit access </div>
								<div class="t m0 x4  y16c ff2 fs1 fc1 sc0 ls0 ws0">to your Personal Data to our
									employees on
									a need to know basis. Those processing </div>
								<div class="t m0 x4  y16d ff2 fs1 fc1 sc0 ls0 ws0">your Personal Data will only do so in
									an
									authorised manner and are required to treat </div>
								<div class="t m0 x4  y16e ff2 fs1 fc1 sc0 ls0 ws0">your information with
									confidentiality.
								</div>
								<div class="t m0 x4  y16f ff2 fs1 fc1 sc0 ls0 ws0">Nevertheless, please understand that
									the
									transmission of i<span class="_ _1"></span>nformation via the </div>
								<div class="t m0 x4  y170 ff2 fs1 fc1 sc0 ls0 ws0">internet is not completely secure.
									Although<span class="_ _1"></span> we will do our best to protect<span
										class="_ _0"></span> </div>
								<div class="t m0 x4  y171 ff2 fs1 fc1 sc0 ls0 ws0">your Personal Data, we cannot
									guarantee
									the security of your Personal </div>
								<div class="t m0 x4  y172 ff2 fs1 fc1 sc0 ls0 ws0">Data transmitted through any online
									means, therefore, any tra<span class="_ _1"></span>nsmission remains at </div>
								<div class="t m0 x4  y173 ff2 fs1 fc1 sc0 ls0 ws0">your own risk. </div>
								<div class="t m0 x4  y174 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y12a ff1 fs1 fc1 sc0 ls3 ws0">8.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Your rights with respect to your
											personal
											data </span></span></div>
								<div class="t m0 x4  y175 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y176 ff2 fs1 fc1 sc0 ls0 ws0">In accordance with applicable laws
									and
									regulations, you may be entitled to: </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pfc" class="pf w0 h0" data-page-no="c">
						<div class="pc pcc w0 h0"><img class="bi x7 y177 w3 h10" alt="" src="bgc.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y27 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Ask us about the processing of your Personal Data,
											including to be provided with a </span></span></div>
								<div class="t m0 x4  y28 ff2 fs1 fc2 sc0 ls0 ws0">copy of your Personal Data; </div>
								<div class="t m0 x4  y29 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y2a ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Request the correction and/or (in some cases)
											deletion
											of your Personal Data; </span></span></div>
								<div class="t m0 x4  y2b ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y2c ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">In some cases, request the restriction of the
											processing of your Personal Data, or </span></span></div>
								<div class="t m0 x4  y2d ff2 fs1 fc2 sc0 ls0 ws0">object to that processing; </div>
								<div class="t m0 x4  y2e ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y2f ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Withdraw your consent to the processing of your
											Personal Data (<span class="_ _1"></span>where we are </span></span></div>
								<div class="t m0 x4  y30 ff2 fs1 fc2 sc0 ls0 ws0">processing your Personal Data based on
									your consent); </div>
								<div class="t m0 x4  y31 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y32 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Request receipt or transmission to another
											organisation, in a machine-readable </span></span></div>
								<div class="t m0 x4  y33 ff2 fs1 fc2 sc0 ls0 ws0">form, of the Personal Data that you
									have
									provided to us where we are using your </div>
								<div class="t m0 x4  y34 ff2 fs1 fc2 sc0 ls0 ws0">Personal Data based on consent or
									performance of a contract; a<span class="_ _1"></span>nd<span class="_ _0"></span>
								</div>
								<div class="t m0 x4  y35 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y36 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Complain to the relevant data privacy authority if
											your
											data privacy rights are </span></span></div>
								<div class="t m0 x4  y37 ff2 fs1 fc2 sc0 ls0 ws0">violated, or if you have suffered as a
									result of unlawful processin<span class="_ _0"></span>g of your Personal </div>
								<div class="t m0 x4  y38 ff2 fs1 fc2 sc0 ls0 ws0">Data. </div>
								<div class="t m0 x4  y39 ff2 fs1 fc1 sc0 ls0 ws0">Where you are given the option to
									share
									your Personal Data with<span class="_ _1"></span> us, you can always </div>
								<div class="t m0 x4  y178 ff2 fs1 fc1 sc0 ls0 ws0">choose not to do so. If we have
									requested
									your consent to processing and you later </div>
								<div class="t m0 x4  y179 ff2 fs1 fc1 sc0 ls0 ws0">choose to withdraw it, we will
									respect
									that choice<span class="_ _1"></span> in accordanc<span class="_ _0"></span>e with
									our
									legal </div>
								<div class="t m0 x4  y17a ff2 fs1 fc1 sc0 ls0 ws0">obligations. </div>
								<div class="t m0 x4  y17b ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y17c ff2 fs1 fc1 sc0 ls0 ws0">However, choosing not to share your
									Personal Data with us or withdrawi<span class="_ _1"></span>ng your </div>
								<div class="t m0 x4  y17d ff2 fs1 fc1 sc0 ls0 ws0">consent to our use of it could mean
									that
									we are unable to perform the actions </div>
								<div class="t m0 x4  y17e ff2 fs1 fc1 sc0 ls0 ws0">necessary to achieve the purposes of
									processing described in Section II (Use of </div>
								<div class="t m0 x4  y17f ff2 fs1 fc1 sc0 ls0 ws0">Personal Data) or that you are unable
									to
									make us<span class="_ _1"></span>e of the Services. After you have </div>
								<div class="t m0 x4  y180 ff2 fs1 fc1 sc0 ls0 ws0">chosen to withdraw your consent, we
									may
									be able to continue to process your </div>
								<div class="t m0 x4  y181 ff2 fs1 fc1 sc0 ls0 ws0">Personal Data to the extent required
									or
									otherwise permit<span class="_ _1"></span>ted by applicable laws and </div>
								<div class="t m0 x4  y182 ff2 fs1 fc1 sc0 ls0 ws0">regulations. </div>
								<div class="t m0 x4  y183 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y184 ff2 fs1 fc1 sc0 ls0 ws0">If you wish to make a request to
									exercise
									your rights, you can contact us t<span class="_ _1"></span>hrough our </div>
								<div class="t m0 x4  y185 ff2 fs1 fc1 sc0 ls0 ws0">contact details set out in Section
									<span class="ls3">10</span> (How to Contact Us) below. </div>
								<div class="t m0 x4  y186 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y187 ff2 fs1 fc1 sc0 ls0 ws0">We will screen and verify all
									requests
									beforehand. In order to verify your a<span class="_ _1"></span>uthority to </div>
								<div class="t m0 x4  y188 ff2 fs1 fc1 sc0 ls0 ws0">make the request, we may require you
									to
									provid<span class="_ _1"></span>e supporting information or </div>
								<div class="t m0 x4  y8f ff2 fs1 fc1 sc0 ls0 ws0">documentation to corroborate the
									request.
								</div>
								<div class="t m0 x4  y189 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y18a ff2 fs1 fc1 sc0 ls0 ws0">Once verified, we will give effect
									<span class="ls4">to</span> your request within the timelines prescribed by </div>
								<div class="t m0 x4  y18b ff2 fs1 fc1 sc0 ls0 ws0">applicable laws. </div>
								<div class="t m0 x4  y18c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y18d ff1 fs1 fc1 sc0 ls3 ws0">9.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Amendments and Updates </span></span>
								</div>
								<div class="t m0 x4  y18e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y18f ff2 fs1 fc1 sc0 ls0 ws0">gogo20 shall have the right to
									modify,
									update or amend the terms<span class="_ _1"></span> of this Policy at </div>
								<div class="t m0 x4  y190 ff2 fs1 fc1 sc0 ls0 ws0">any time by placing the updated
									Policy on
									the Websites and Apps. By continuing to </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pfd" class="pf w0 h0" data-page-no="d">
						<div class="pc pcd w0 h0"><img class="bi x8 y191 w4 h11" alt="" src="bgd.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x4  y27 ff2 fs1 fc1 sc0 ls0 ws0">use the Apps, Websites or Services,
									purchase products from gogo20 or continuing to </div>
								<div class="t m0 x4  y14d ff2 fs1 fc1 sc0 ls0 ws0">communicate or engage with gogo<span
										class="ls3">20</span> following the modifications, updates or </div>
								<div class="t m0 x4  y14e ff2 fs1 fc1 sc0 ls0 ws0">amendments to this Policy, you
									signify
									your acceptance of such modificat<span class="_ _1"></span>ions, </div>
								<div class="t m0 x4  y14f ff2 fs1 fc1 sc0 ls0 ws0">updates or amendments. </div>
								<div class="t m0 x4  y150 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y151 ff1 fs1 fc1 sc0 ls3 ws0">10.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">How to contact us </span></span></div>
								<div class="t m0 x4  y152 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y153 ff2 fs1 fc1 sc0 ls0 ws0">If you have any queries about this
									Policy
									or would like to exercise your rights set out </div>
								<div class="t m0 x4  y154 ff2 fs1 fc1 sc0 ls0 ws0">in this Policy, please contact our
									Corporate Communication <span class="_ _0"></span>Officer at: </div>
								<div class="t m0 x4  y155 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y156 ff1 fs1 fc1 sc0 ls0 ws0">iSelect Pvt. Ltd.<span
										class="ff2"> </span></div>
								<div class="t m0 x4  y157 ff2 fs1 fc1 sc0 ls0 ws0">Goreto Tower, Dhumbarahi, Chandol,
									Kathmandu, Nepal </div>
								<div class="t m0 x4  y158 ff2 fs1 fc1 sc0 ls0 ws0">email: <span
										class="fc3">contact@gogo20.com</span> </div>
								<div class="t m0 x4  y159 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y15a ff2 fs1 fc1 sc0 ls0 ws0">The original of this Policy is
									written in
									the English language. In the event of any </div>
								<div class="t m0 x4  y15b ff2 fs1 fc1 sc0 ls0 ws0">conflict between the English and
									other
									language v<span class="_ _1"></span>ersions, the English version shall </div>
								<div class="t m0 x4  y15c ff2 fs1 fc1 sc0 ls0 ws0">prevail. </div>
								<div class="t m0 x4  y192 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y193 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y194 ff1 fs1 fc1 sc0 ls0 ws0">Addendum 1: gogo20 for Business
								</div>
								<div class="t m0 x4  y195 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x9 h5 y196 ff1 fs1 fc1 sc0 ls3 ws0">1.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Interpretation </span></span></div>
								<div class="t m0 x4  y197 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y198 ff2 fs1 fc1 sc0 ls0 ws0">All capitalised terms but undefined
									terms
									used herein s<span class="_ _1"></span>hall bear the same meaning </div>
								<div class="t m0 x4  y199 ff2 fs1 fc1 sc0 ls0 ws0">as those defined in the Terms of Use
									and
									the gogo20 Privacy Policy (accessible at </div>
								<div class="t m0 x4  y19a ff2 fs1 fc1 sc0 ls0 ws0">https://www.gogo20.com/privacy).
								</div>
								<div class="t m0 x4  y19b ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  ybf ff2 fs1 fc1 sc0 ls0 ws0">This Addendum forms part of the gogo20
									Privacy Policy. In the event of any </div>
								<div class="t m0 x4  y19c ff2 fs1 fc1 sc0 ls0 ws0">inconsistency between the gogo20
									Privacy
									Policy and this Addendum, this </div>
								<div class="t m0 x4  y100 ff2 fs1 fc1 sc0 ls0 ws0">Addendum shall prevail. </div>
								<div class="t m0 xa  y19d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x9 h5 y19e ff1 fs1 fc1 sc0 ls3 ws0">2.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">gogo20<span class="ff4">’s
												relationship
												with users and clients</span> </span></span></div>
								<div class="t m0 xa  y19f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y1a0 ff1 fs1 fc1 sc0 ls0 ws0">How gogo20 works for business </div>
								<div class="t m0 x4  y1a1 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y1a2 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 for Business is provided as an
									add-on f<span class="_ _0"></span>eature to facilitate corporat<span
										class="_ _1"></span>e billing </div>
								<div class="t m0 x4  y1a3 ff3 fs1 fc1 sc0 ls0 ws0">for the Authorised Users’ use of
									<span class="ff2">gogo20</span>’s Services in the course of work.<span class="ff2">
									</span></div>
								<div class="t m0 x4  y1a4 ff3 fs1 fc1 sc0 ls0 ws0">When an organisation (“Client”)
									chooses
									to utilise <span class="ff2">gogo20 for Business, the </span></div>
								<div class="t m0 x4  y1a5 ff2 fs1 fc1 sc0 ls0 ws0">Authorised User is given the option
									of
									tagging his<span class="_ _1"></span>/her rides or other t<span
										class="_ _0"></span>ransactions to </div>
								<div class="t m0 x4  y1a6 ff2 fs1 fc1 sc0 ls0 ws0">the Client or to tag it as a personal
									ride. An Authorised User is referred <span class="_ _1"></span>to in the </div>
								<div class="t m0 x4  y1a7 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 Privacy Policy as a passenger.
								</div>
								<div class="t m0 x4  y1a8 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y1a9 ff2 fs1 fc1 sc0 ls0 ws0">As part of this feature, gogo20 will
									disclose detailed trip and booking information </div>
								<div class="t m0 x4  y1aa ff2 fs1 fc1 sc0 ls0 ws0">that Authorised Users have tagged as
									being for business purposes to the Client. </div>
							</div><a class="l" href="mailto:contact@gogo20.com">
								<div class="d m1"
									style="border-style:none;position:absolute;left:138.490000px;bottom:564.410000px;width:111.930000px;height:15.810000px;background-color:rgba(255,255,255,0.000001);">
								</div>
							</a>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pfe" class="pf w0 h0" data-page-no="e">
						<div class="pc pce w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x4  y27 ff2 fs1 fc1 sc0 ls0 ws0">Apart from this, gogo20 does not
									disclose
									other Personal Data of its Authorised </div>
								<div class="t m0 x4  y14d ff2 fs1 fc1 sc0 ls0 ws0">Users to the Client. </div>
								<div class="t m0 x4  y14e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y14f ff2 fs1 fc1 sc0 ls0 ws0">Alternatively, an individual user may
									choose to set up a business profile within the </div>
								<div class="t m0 x4  y150 ff2 fs1 fc1 sc0 ls0 ws0">App to facilitate the tagging of
									business-related rides and to generate consolidated </div>
								<div class="t m0 x4  y151 ff2 fs1 fc1 sc0 ls0 ws0">trip reports to facilitate the
									submission
									of claims from his or her employer.<span class="_ _1"></span> When </div>
								<div class="t m0 x4  y152 ff2 fs1 fc1 sc0 ls0 ws0">used in this mode, the claims process
									is
									user-<span class="ff3">driven <span class="_ _0"></span>and the user’s employer need
									</span></div>
								<div class="t m0 x4  y153 ff2 fs1 fc1 sc0 ls0 ws0">not be a Client. </div>
								<div class="t m0 x4  y154 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y155 ff2 fs1 fc1 sc0 ls0 ws0">For ease of reference, Authorised
									Users
									and individual users will eac<span class="_ _1"></span>h be referred to </div>
								<div class="t m0 x4  y156 ff3 fs1 fc1 sc0 ls0 ws0">as a “User” and collectively as
									“Users”
									in this Addendum.<span class="ff2"> </span></div>
								<div class="t m0 x4  y157 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y158 ff1 fs1 fc1 sc0 ls0 ws0">gogo20 is a data controller, so are
									our
									Clients </div>
								<div class="t m0 x4  y159 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y15a ff2 fs1 fc1 sc0 ls0 ws0">In respect of any User and the
									processing
									of all th<span class="_ _1"></span>eir Personal Data (in<span
										class="_ _0"></span>cluding but not </div>
								<div class="t m0 x4  y15b ff2 fs1 fc1 sc0 ls0 ws0">limited to Linking Data and Portal
									Data),
									gogo20 acts as a data controller. For further </div>
								<div class="t m0 x4  y15c ff2 fs1 fc1 sc0 ls0 ws0">information on how gogo20 <span
										class="ff3">collects, uses and discloses Users’ </span>Personal Data, please
								</div>
								<div class="t m0 x4  y15d ff2 fs1 fc1 sc0 ls0 ws0">refer to the gogo20 Privacy Policy.
								</div>
								<div class="t m0 x4  y15e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y15f ff2 fs1 fc1 sc0 ls0 ws0">Due to the way gogo20 for Business
									works,
									gogo29 does not process any Personal </div>
								<div class="t m0 x4  y160 ff2 fs1 fc1 sc0 ls0 ws0">Data for and on behalf of the Client.
									Accordingly, gogo20 <span class="_ _0"></span>is not the data processor o<span
										class="_ _1"></span>f </div>
								<div class="t m0 x4  y161 ff2 fs1 fc1 sc0 ls0 ws0">the Client, but an independent data
									controller in respect of all Personal Data that it </div>
								<div class="t m0 x4  y162 ff2 fs1 fc1 sc0 ls0 ws0">processes in the course of providing
									the
									gogo20 for Business feature. Likewise, the </div>
								<div class="t m0 x4  y163 ff2 fs1 fc1 sc0 ls0 ws0">Client is an independent data
									controller
									of the Personal Data (e.g. the Linking Data </div>
								<div class="t m0 x4  y164 ff2 fs1 fc1 sc0 ls0 ws0">and Portal Data) that it discloses to
									and/or receives from gogo20. </div>
								<div class="t m0 x4  y165 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y166 ff2 fs1 fc1 sc0 ls0 ws0">As independent controllers, gogo20
									and
									the Client individually determine the </div>
								<div class="t m0 x4  y167 ff2 fs1 fc1 sc0 ls0 ws0">purposes and means of processing
									Personal
									Data, subject to the provisions set out in </div>
								<div class="t m0 x4  y168 ff2 fs1 fc1 sc0 ls0 ws0">the Terms of Use and this Privacy
									Policy.
									gogo20 and the Client are also individually </div>
								<div class="t m0 x4  y169 ff2 fs1 fc1 sc0 ls0 ws0">responsible to ensure the protection
									of
									Personal Data under their charge. </div>
								<div class="t m0 xa  y16a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x9 h5 y16b ff1 fs1 fc1 sc0 ls3 ws0">3.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">What personal data is collected,
											processed and <span class="_ _1"></span>disclosed </span></span></div>
								<div class="t m0 x4  y16c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y16d ff1 fs1 fc1 sc0 ls0 ws0">What gogo20 collects as part of
									gogo20
									for Business </div>
								<div class="t m0 x4  y16e ff2 fs1 fc1 sc0 ls0 ws0">In order to provide the gogo20 for
									Business feature, the individual user or the Client </div>
								<div class="t m0 x4  y16f ff2 fs1 fc1 sc0 ls0 ws0">will be required to provide the
									following
									information about the Authorised User to </div>
								<div class="t m0 x4  y170 ff2 fs1 fc1 sc0 ls0 ws0">gogo20: </div>
								<div class="t m0 x4  y171 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y172 ff2 fs1 fc1 sc0 ls0 ws0">Full name; </div>
								<div class="t m0 x4  y173 ff2 fs1 fc1 sc0 ls0 ws0">Business email address; and </div>
								<div class="t m0 x4  y174 ff2 fs1 fc1 sc0 ls0 ws0">Other identifying information about
									the
									Authorised User as reasonably requested by </div>
								<div class="t m0 x4  y12a ff2 fs1 fc1 sc0 ls0 ws0">gogo20. </div>
								<div class="t m0 x4  y175 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y176 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 will use this information for
									the
									purposes of: </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pff" class="pf w0 h0" data-page-no="f">
						<div class="pc pcf w0 h0"><img class="bi x8 y1ab w4 h12" alt="" src="bgf.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x4  y27 ff2 fs1 fc1 sc0 ls0 ws0">authenticating the Use<span
										class="ls7">r;</span> <span class="ff3">where applicable, linking the Authorised
										User’s ac<span class="_ _1"></span>count </span></div>
								<div class="t m0 x4  y14d ff3 fs1 fc1 sc0 ls0 ws0">with the Client’s <span
										class="ff2">gogo20 for Business account or tracking the Autho</span>rised User’s
								</div>
								<div class="t m0 x4  y14e ff2 fs1 fc1 sc0 ls0 ws0">business profile bookings on the
									Apps, as
									the case may be; </div>
								<div class="t m0 x4  y14f ff2 fs1 fc1 sc0 ls0 ws0">where applicable, verifying the
									Corporate<span class="_ _1"></span> Billing status of<span class="_ _0"></span> such
									Authorised User from </div>
								<div class="t m0 x4  y150 ff2 fs1 fc1 sc0 ls0 ws0">time to time; and contacting the User
									in
									accordance with the purposes set out in the </div>
								<div class="t m0 x4  y151 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 Privacy Policy. </div>
								<div class="t m0 x4  y152 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y153 ff2 fs1 fc1 sc0 ls0 ws0">Upon onboarding such User to the App,
									gogo20 will process the Personal Data of the </div>
								<div class="t m0 x4  y154 ff2 fs1 fc1 sc0 ls0 ws0">User in accordance with the gogo20
									Privacy Policy and this Addendum. </div>
								<div class="t m0 x4  y155 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y156 ff1 fs1 fc1 sc0 ls0 ws0">What gogo20 discloses to its Clients
								</div>
								<div class="t m0 x4  y157 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y158 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 will disclose relevant trip
									and
									booking information as de<span class="_ _1"></span>termined by <span
										class="_ _0"></span>gogo20 </div>
								<div class="t m0 x4  y159 ff2 fs1 fc1 sc0 ls0 ws0">from time to time to the Client to
									facilitate Corporate Billing. </div>
								<div class="t m0 x4  y15a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y15b ff1 fs1 fc1 sc0 ls0 ws0">What gogo20 discloses to its
									individual
									user </div>
								<div class="t m0 x4  y15c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y15d ff2 fs1 fc1 sc0 ls0 ws0">Depending on the business profile
									settings selected by the i<span class="_ _1"></span>ndividu<span
										class="_ _0"></span>al
									user, the user </div>
								<div class="t m0 x4  y15e ff2 fs1 fc1 sc0 ls0 ws0">may retrieve and generate reports
									containing his<span class="_ _1"></span>/her relevant trip and booking </div>
								<div class="t m0 x4  y15f ff2 fs1 fc1 sc0 ls0 ws0">information. </div>
								<div class="t m0 xa  y160 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x9 h5 y161 ff1 fs1 fc1 sc0 ls3 ws0">4.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff4">Parties’ obligations<span
												class="ff1">
											</span></span></span></div>
								<div class="t m0 xa  y162 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y163 ff2 fs1 fc1 sc0 ls0 ws0">The Client and gogo20 shall each:
								</div>
								<div class="t m0 x4  y1ac ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Individually inform their data subjects of how each
											processes Personal<span class="_ _1"></span> Data and </span></span></div>
								<div class="t m0 x9  y1ad ff2 fs1 fc2 sc0 ls0 ws0">allow their data subjects to exercise
									their rights under the local data </div>
								<div class="t m0 x9  y1ae ff2 fs1 fc2 sc0 ls0 ws0">protection/data privacy laws; </div>
								<div class="t m0 x9  y1af ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y1b0 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Comply with the obligations applicable to each
											p<span class="_ _1"></span>arty under the applicable data </span></span>
								</div>
								<div class="t m0 x9  y1b1 ff2 fs1 fc2 sc0 ls0 ws0">protection/data privacy laws when
									processing any Personal Data of the Proposed </div>
								<div class="t m0 x9  y1b2 ff2 fs1 fc2 sc0 ls0 ws0">or Authorised Users; </div>
								<div class="t m0 x9  y1b3 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y143 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Obtain the necessary consents (if applicable) to
											facilitate the provision of the </span></span></div>
								<div class="t m0 x9  y1b4 ff2 fs1 fc2 sc0 ls0 ws0">gogo20 for Business feature; and
								</div>
								<div class="t m0 x9  y1b5 ff2 fs1 fc2 sc0 ls0 ws0"> </div>
								<div class="t m0 x4  y1b6 ff7 fs2 fc2 sc0 ls0 ws0">▪<span class="ff8"> <span
											class="_ _3">
										</span><span class="ff2 fs1">Implement appropriate legal, technical and
											organisational measures to protect </span></span></div>
								<div class="t m0 x9  y1b7 ff2 fs1 fc2 sc0 ls0 ws0">Personal Data against unauthorised or
									unlawful processing and against </div>
								<div class="t m0 x9  y1b8 ff2 fs1 fc2 sc0 ls0 ws0">unauthorised loss, destruction,
									damage,
									alteration, or disclosure, as<span class="_ _1"></span> well as any </div>
								<div class="t m0 x9  y1b9 ff3 fs1 fc2 sc0 ls0 ws0">breach or attempted breach of each
									party’s security measures (“<span class="ff1">Information </span></div>
								<div class="t m0 x9  y23 ff1 fs1 fc2 sc0 ls0 ws0">Security Incident<span
										class="ff3">”).<span class="ff2"> </span></span></div>
								<div class="t m0 x2 h13 y1ba ff1 fs3 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h13 y1bb ff1 fs3 fc1 sc0 ls0 ws0"> <span class="_ _6"> </span>
								</div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf10" class="pf w0 h0" data-page-no="10">
						<div class="pc pc10 w0 h0"><img class="bi x2 y1bc w5 h14" alt="" src="bg10.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2 y2 ff1 fs0 fc0 sc0 ls0 ws0">Terms &amp; Conditio<span
										class="_ _1"></span>ns </div>
								<div class="t m0 x2  y3 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 for business terms: Transport,
									On-Demand and Last Mile Delivery &amp; Logistics </div>
								<div class="t m0 x2  y1bd ff1 fs1 fc1 sc0 ls0 ws0">Terms of Use </div>
								<div class="t m0 x2  y1be ff1 fs1 fc1 sc0 ls0 ws0">Section A <span class="ff4">–</span>
									General Terms relating to gogo20 services </div>
								<div class="t m0 x2  y1bf ff2 fs1 fc1 sc0 ls0 ws0">iSelect Pvt. Lt<span
										class="ls4">d.</span>, is a company incorporated under the laws of Nepal, with
									its
								</div>
								<div class="t m0 x2  y1c0 ff2 fs1 fc1 sc0 ls0 ws0">registered office at Goreto Tower,
									Dhumbarahi, Chandol, Kathmandu, Nepal. </div>
								<div class="t m0 x2 hf y1c1 ff2 fs1 fc1 sc0 ls3 ws0">1.<span class="ff8 ls0"> <span
											class="_ _2"> </span><span class="ff1">Introd<span
												class="_ _0"></span>uction
										</span></span></div>
								<div class="t m0 x3  y1c2 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1c3 ff2 fs1 fc1 sc0 ls0 ws0">1.1.<span class="ff8"> <span
											class="ff1"> </span></span>Please read these Terms of Use carefully. By
									using
									the Service (as defined belo<span class="_ _1"></span>w), </div>
								<div class="t m0 xb  y1c4 ff2 fs1 fc1 sc0 ls0 ws0">you agree that you have read and
									understood the terms in these Terms of Use </div>
								<div class="t m0 xb  y1c5 ff2 fs1 fc1 sc0 ls0 ws0">which are applicable to you. These
									Terms
									of Use and the <span class="lsa">go<span class="_ _0"></span></span>go20 Policies
									(as
									defined </div>
								<div class="t m0 xb  y1c6 ff2 fs1 fc1 sc0 ls0 ws0">below) consti<span class="ff3">tute a
										legally binding agreement (“<span class="ff1">Agreement</span>”) between you and
									</span></div>
								<div class="t m0 xb  y1c7 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 (as defined below). The
									Agreement
									applies to your use of the Service </div>
								<div class="t m0 xb  yf ff2 fs1 fc1 sc0 ls0 ws0">provided by gogo20. If you do not agree
									to
									the Terms of Use please do not use or </div>
								<div class="t m0 xb  y1c8 ff2 fs1 fc1 sc0 ls0 ws0">continue using the Platform (as
									defined
									below) or the Service. </div>
								<div class="t m0 xb  y1c9 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1ca ff2 fs1 fc1 sc0 ls0 ws0">1.2.<span class="ff8"> </span>
									gogo20
									may amend the terms in the Agreement at any time. Such amendments </div>
								<div class="t m0 xb  y1cb ff2 fs1 fc1 sc0 ls0 ws0">shall be effective once they are
									posted
									on <span class="fc4">http<span class="_ _0"></span>s://www.gogo20.com</span> or the
								</div>
								<div class="t m0 xb  y1cc ff2 fs1 fc1 sc0 ls0 ws0">Application. It is your
									responsibility to
									review the Terms of Use an<span class="_ _1"></span>d <span
										class="_ _0"></span>gogo20
									Policies </div>
								<div class="t m0 xb  y1cd ff2 fs1 fc1 sc0 ls0 ws0">regularly. Your continued use of the
									Service after<span class="_ _1"></span> any such amendments, whether or </div>
								<div class="t m0 xb  y1ce ff2 fs1 fc1 sc0 ls0 ws0">not reviewed by you, shall constitute
									your agreement to be bound by such </div>
								<div class="t m0 xb  y1cf ff2 fs1 fc1 sc0 ls0 ws0">amendments. </div>
								<div class="t m0 xb  y1d0 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1d1 ff2 fs1 fc1 sc0 ls0 ws0">1.3.<span class="ff8"> </span> If
									you
									use the Service in a country other than the country where you regist<span
										class="_ _1"></span>ered for </div>
								<div class="t m0 xb  y1d2 ff3 fs1 fc1 sc0 ls0 ws0">the Application (the “Alternate
									Country“), you must regularly review the Terms of </div>
								<div class="t m0 xb  y1d3 ff2 fs1 fc1 sc0 ls0 ws0">Service applicable in the Alternate
									Country which can be found </div>
								<div class="t m0 xb  y1d4 ff2 fs1 fc1 sc0 ls7 ws0">at<span class="ls0"> <span
											class="fs3 fc3">https://www.gogo<span class="_ _1"></span>20.com<span
												class="fs1 fc1"> as it may differ from the country where<span
													class="_ _1"></span> you registered </span></span></span></div>
								<div class="t m0 xb  y1d5 ff2 fs1 fc1 sc0 ls0 ws0">for the Application. By using the
									Service
									in the Alternate Country, you agre<span class="_ _1"></span>e to be </div>
								<div class="t m0 xb  y1d6 ff2 fs1 fc1 sc0 ls0 ws0">bound by prevailing Terms of Use in
									the
									Alternate Country. </div>
								<div class="t m0 xb  y1d7 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1d8 ff2 fs1 fc1 sc0 ls0 ws0">1.4.<span class="ff8">
									</span>gogo20 IS
									A TECHNOLOGY COMPANY WHICH PROVIDES A PLATFORM FOR USERS TO </div>
								<div class="t m0 xb  y1d9 ff2 fs1 fc1 sc0 ls0 ws0">OBTAIN OR PROCURE SERVICES. DEPENDING
									ON
									THE SERVICE IN QUESTION, THE </div>
								<div class="t m0 xb  y1da ff2 fs1 fc1 sc0 ls0 ws0">SERVICES MAY BE SUPPLIED BY gogo20 OR
									A
									THIRD-PARTY PROVIDER. WHERE THE </div>
								<div class="t m0 xb  y1db ff2 fs1 fc1 sc0 ls0 ws0">SERVICE IS PROVIDED BY A THIRD-PARTY
									PROVIDER, gogo20<span class="ff3">’S ROLE IS MERELY TO </span></div>
								<div class="t m0 xb  y106 ff2 fs1 fc1 sc0 ls0 ws0">LINK THE USER WITH SUCH THIRD-PARTY
									PROVIDER. gogo20 <span class="_ _0"></span>IS NOT RESP<span
										class="_ _1"></span>ONSIBLE
								</div>
								<div class="t m0 xb  y1dc ff2 fs1 fc1 sc0 ls0 ws0">FOR THE ACTS AND/OR OMISSIONS OF ANY
									THIRD-PARTY PROVIDER, AND ANY </div>
								<div class="t m0 xb  y1dd ff2 fs1 fc1 sc0 ls0 ws0">LIABILITY IN RELATION TO SUCH
									SERVICES
									SHALL BE BORNE BY THE THI<span class="ls2">RD</span>-<span class="_ _0"></span>PARTY
								</div>
								<div class="t m0 xb  y1de ff2 fs1 fc1 sc0 ls0 ws0">PROVIDER. THIRD-PARTY PROVIDERS SHALL
									NOT
									REPRESENT TO BE AN AGENT, </div>
								<div class="t m0 xb  y1df ff2 fs1 fc1 sc0 ls0 ws0">EMPLOYEE OR STAFF OF gogo20 AND THE
									SOLUTIONS PROVIDED BY THIRD-PA<span class="_ _0"></span>RTY </div>
								<div class="t m0 xb  y1e0 ff2 fs1 fc1 sc0 ls0 ws0">PROVIDERS SHALL NOT BE DEEMED TO BE
									PROVIDED BY <span class="_ _0"></span>gogo20. </div>
								<div class="t m0 xb  y1e1 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
							</div><a class="l" href="https://www.gogo20.com/">
								<div class="d m1"
									style="border-style:none;position:absolute;left:121.830000px;bottom:300.470000px;width:120.660000px;height:15.800000px;background-color:rgba(255,255,255,0.000001);">
								</div>
							</a>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf11" class="pf w0 h0" data-page-no="11">
						<div class="pc pc11 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2 h5 y27 ff1 fs1 fc1 sc0 ls3 ws0">2.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Registration </span></span></div>
								<div class="t m0 x3  y14d ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y14e ff2 fs1 fc1 sc0 ls0 ws0">2.1.<span class="ff8"> </span>You
									shall
									be permitted to access the Platform, avail the <span class="_ _0"></span>gogo20
									Services
									and </div>
								<div class="t m0 xb  y14f ff2 fs1 fc1 sc0 ls0 ws0">connect with Delivery Partner on the
									Platform after completi<span class="_ _1"></span>ng the onboarding </div>
								<div class="t m0 xb  y150 ff3 fs1 fc1 sc0 ls0 ws0">process which shall be an Application
									Program Interface (“API”) i<span class="_ _1"></span>ntegration with </div>
								<div class="t m0 xb  y151 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 or in case where <span
										class="ls3">Ve</span>ndor / Merchant is not capable to do the API </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">integration, in a manner as may be
									informed by gogo20 to Merchant from time to </div>
								<div class="t m0 xb  y153 ff2 fs1 fc1 sc0 ls0 ws0">time at gogo20<span class="ff3">’s
										sole
										discretion.</span> </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y155 ff2 fs1 fc1 sc0 ls0 ws0">2.2.<span class="ff8"> </span>When
									You
									register with gogo20, <span class="_ _0"></span>you will be requi<span
										class="_ _1"></span>red to provide information about </div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls0 ws0">You and/or Your organisation. You
									agree
									and accept that as on the date of your </div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0">registration on the Platform, the
									information provided by you is complete, accurate </div>
								<div class="t m0 xb  y158 ff2 fs1 fc1 sc0 ls0 ws0">and up-<span
										class="ls4">to</span>-date.
									In the event of any change to suc<span class="_ _1"></span>h information, <span
										class="_ _0"></span>you shall be </div>
								<div class="t m0 xb  y159 ff2 fs1 fc1 sc0 ls0 ws0">required to promptly inform gogo20 of
									the
									same, in writing, at least 1 (one) week </div>
								<div class="t m0 xb  y15a ff2 fs1 fc1 sc0 ls0 ws0">prior to the date on which such
									change
									shall take <span class="_ _1"></span>effect. You acknowledge and </div>
								<div class="t m0 xb  y15b ff2 fs1 fc1 sc0 ls0 ws0">accept that gogo20 has not
									independently
									verified the information provided by you. </div>
								<div class="t m0 xb  y15c ff2 fs1 fc1 sc0 ls0 ws0">gogo20 shall in no way be responsible
									or
									liable for the accuracy, inaccuracy, </div>
								<div class="t m0 xb  y15d ff2 fs1 fc1 sc0 ls0 ws0">obsolescence or completeness of any
									information provided by you. If You provide </div>
								<div class="t m0 xb  y15e ff2 fs1 fc1 sc0 ls0 ws0">any information that is untrue,
									inaccurate, obsolete or incomplete, or gogo20 has </div>
								<div class="t m0 xb  y15f ff2 fs1 fc1 sc0 ls0 ws0">reasonable grounds to suspect that
									such
									information is untrue, inacc<span class="_ _0"></span>urate, obsolete </div>
								<div class="t m0 xb  y160 ff2 fs1 fc1 sc0 ls0 ws0">or incomplete, gogo20 reserves the
									right
									to suspend or terminate your Account </div>
								<div class="t m0 xb  y161 ff2 fs1 fc1 sc0 ls0 ws0">(defined below) and refuse any and
									all
									current or future use of the Plat<span class="_ _1"></span>form (or any </div>
								<div class="t m0 xb  y162 ff2 fs1 fc1 sc0 ls0 ws0">portion thereof) at any time. </div>
								<div class="t m0 x3  y163 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y164 ff1 fs1 fc1 sc0 ls3 ws0">3.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">gogo20 Services </span></span></div>
								<div class="t m0 x3  y165 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y166 ff2 fs1 fc1 sc0 ls0 ws0">3.1.<span class="ff8">
									</span>gogo20
									provides you with the following services (<span class="ff3">“</span>gogo20 s<span
										class="ff3">ervices”</span>): </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y168 ff2 fs1 fc1 sc0 ls0 ws0">3.1.1.<span class="ff8"> <span
											class="_ _2"> </span></span>It provides You with a license to access the
									Platform; </div>
								<div class="t m0 xc  y169 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y16a ff2 fs1 fc1 sc0 ls0 ws0">3.1.2.<span class="ff8"> <span
											class="_ _2"> </span></span>The Platform allows You to connect with Delivery
									Partner to pick up and dr<span class="_ _1"></span>op </div>
								<div class="t m0 xc  y16b ff2 fs1 fc1 sc0 ls0 ws0">off packages from one location to the
									other through the Delivery Partner (<span class="ff3">“Pick</span> </div>
								<div class="t m0 xc  y16c ff2 fs1 fc1 sc0 ls9 ws0">Up<span class="ls0"> and Drop Off
										<span class="ff3">Services”</span>); and </span></div>
								<div class="t m0 xc  y16d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 xb  y16e ff2 fs1 fc1 sc0 ls0 ws0">Facilitates the collection of
									payments
									for the transaction/(s) between You and </div>
								<div class="t m0 xb  y16f ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner. </div>
								<div class="t m0 xb  y170 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y171 ff2 fs1 fc1 sc0 ls0 ws0">3.2.<span class="ff8"> </span>You
									can
									initiate a transaction on the Platform by which You may (through the </div>
								<div class="t m0 xb  y172 ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner) send packages to a
									particular location identified by You.<span class="_ _1"></span> The Pick </div>
								<div class="t m0 xb  y173 ff2 fs1 fc1 sc0 ls0 ws0">Up and Drop Off Services are provided
									to
									You directly by the Delivery Par<span class="_ _1"></span>tner and </div>
								<div class="t m0 xb  y174 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 merely acts as a technology
									platform to facilitate the connec<span class="_ _1"></span>t<span
										class="_ _0"></span>ion between </div>
								<div class="t m0 xb  y12a ff2 fs1 fc1 sc0 ls0 ws0">You and the Delivery Partner. The
									Delivery Partner is neither an employee nor an </div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0">agent or an affiliate of gogo20<span
										class="ls9">. </span>gogo20 does not assume any responsibility or </div>
								<div class="t m0 xb  y176 ff2 fs1 fc1 sc0 ls0 ws0">liability for any form of act,
									omission
									to act, services provi<span class="_ _1"></span>ded, quality or deficienc<span
										class="_ _0"></span><span class="ls2">y </span></div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf12" class="pf w0 h0" data-page-no="12">
						<div class="pc pc12 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xb  y27 ff2 fs1 fc1 sc0 ls0 ws0">of services on part of the Delivery
									Partner. You hereby agree and acknowle<span class="_ _1"></span>dge that </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0">all actions, omissions to act,
									services
									provided, quality or deficiency in ser<span class="_ _1"></span>vices with </div>
								<div class="t m0 xb  y14e ff2 fs1 fc1 sc0 ls0 ws0">respect to the Pick Up and Drop Off
									Services is of <span class="_ _1"></span>the Delivery Partner in the </div>
								<div class="t m0 xb  y14f ff2 fs1 fc1 sc0 ls0 ws0">Del<span class="ff3">ivery Partner’s
										independent </span>capacity and sole discretion. </div>
								<div class="t m0 xb  y150 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y151 ff2 fs1 fc1 sc0 ls0 ws0">3.3.<span class="ff8"> </span>Upon
									initiation of a request for Pick Up and Drop Off Services on the Platform, </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner/(s) around the
									pickup
									location shall be intimated in an automated </div>
								<div class="t m0 xb  y153 ff2 fs1 fc1 sc0 ls0 ws0">manner and depending upon the
									availability of Delivery Partner/(s), a Delivery </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0">Partner may choose to accept Your
									request. The Delivery Partner s<span class="_ _1"></span>hall pick up the </div>
								<div class="t m0 xb  y155 ff2 fs1 fc1 sc0 ls0 ws0">item from a location designated by
									You on
									the Platform and <span class="_ _1"></span>drop off the Items at a </div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls0 ws0">particular location designated by
									You.
									While performing the Pick Up and Dro<span class="_ _0"></span>p off </div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0">Services, the Delivery Partner shall
									act
									as Your agent and shall act in<span class="_ _1"></span> accordance </div>
								<div class="t m0 xb  y158 ff2 fs1 fc1 sc0 ls0 ws0">with Your instructions. You agree and
									acknowledge that the pick-up location and </div>
								<div class="t m0 xb  y159 ff2 fs1 fc1 sc0 ls0 ws0">the drop off location will be added
									by
									You and tha<span class="_ _1"></span>t such<span class="_ _0"></span> information
									will
									be used fo<span class="ls7">r </span></div>
								<div class="t m0 xb  y15a ff2 fs1 fc1 sc0 ls0 ws0">the Pick Up and Drop Off Services.
									You
									must ensure that the details for the locations </div>
								<div class="t m0 xb  y15b ff2 fs1 fc1 sc0 ls0 ws0">are accurate and identifiable by the
									Delivery Par<span class="_ _1"></span>tners.<span class="_ _0"></span> </div>
								<div class="t m0 xb  y15c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y15d ff2 fs1 fc1 sc0 ls0 ws0">3.4.<span class="ff8"> </span>You
									agree
									that You shall at all times use the Platf<span class="_ _1"></span>orm and <span
										class="_ _0"></span>gogo20 Services for lawful </div>
								<div class="t m0 xb  y15e ff2 fs1 fc1 sc0 ls0 ws0">purposes. Additionally, You shall not
									use
									the Pick Up and Drop Off Services for items </div>
								<div class="t m0 xb  y15f ff2 fs1 fc1 sc0 ls0 ws0">which are illegal, immoral,
									hazardous,
									unsafe, dangerous, or otherwise res<span class="_ _1"></span>tricted or </div>
								<div class="t m0 xb  y160 ff2 fs1 fc1 sc0 ls0 ws0">constitute items that are prohibited
									by
									any statut<span class="_ _1"></span>e or law or regulation or the </div>
								<div class="t m0 xb  y161 ff2 fs1 fc1 sc0 ls0 ws0">provisions of these Terms of Use.
								</div>
								<div class="t m0 xb  y162 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y163 ff2 fs1 fc1 sc0 ls0 ws0">3.5.<span class="ff8"> </span>You
									agree
									that before requesting a Pick-up and Drop-<span class="_ _0"></span>off Service, You
									are
									<span class="_ _1"></span>well aware </div>
								<div class="t m0 xb  y164 ff2 fs1 fc1 sc0 ls0 ws0">of the contents of the package sent
									or
									requested by You through the Deliv<span class="_ _1"></span>ery </div>
								<div class="t m0 xb  y165 ff2 fs1 fc1 sc0 ls0 ws0">Partner, and that such contents are
									legal
									and wi<span class="_ _1"></span>thin limits<span class="_ _0"></span> of
									transportation/logistics </div>
								<div class="t m0 xb  y166 ff2 fs1 fc1 sc0 ls0 ws0">under applicable law. Such contents
									shall
									not be restricted and/or banne<span class="_ _1"></span>d and/or </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">dangerous and/or prohibited for
									carriage
									(<span class="ff9">such items include, <span class="lsb">but<span
												class="_ _0"></span></span> are not limited to, </span></div>
								<div class="t m0 xb h15 y168 ff9 fs1 fc1 sc0 ls0 ws0">radio-active, incendiary,
									corrosive
									<span class="ls2">or</span> flammable substances, hazardous chemicals, </div>
								<div class="t m0 xb h15 y169 ff9 fs1 fc1 sc0 ls0 ws0">explosives, firearms <span
										class="ls2">or</span> parts thereof <span class="lsb">and</span> <span
										class="_ _0"></span>ammunition, firecrackers, cyanides, </div>
								<div class="t m0 xb h15 y16a ff9 fs1 fc1 sc0 ls0 ws0">precipitates, gold <span
										class="lsb">and</span> silver ore, bullion, precious metals <span
										class="lsb">and</span> stones, <span class="_ _0"></span>jewellery, semi-</div>
								<div class="t m0 xb h15 y16b ff9 fs1 fc1 sc0 ls0 ws0">precious stones including
									commercial
									carbons <span class="ls2">or</span> industrial diamonds, currency </div>
								<div class="t m0 xb h15 y16c ff9 fs1 fc1 sc0 ls0 ws0">(paper <span class="ls2">or</span>
									coin) <span class="ls2">of</span> <span class="lsb">any<span
											class="_ _0"></span></span>
									nationality, securities (including stocks <span class="lsb">and</span> bonds, <span
										class="_ _0"></span>share </div>
								<div class="t m0 xb h15 y16d ff9 fs1 fc1 sc0 ls0 ws0">certificates <span
										class="lsb">and</span> blank signed share transfer forms), coupons, stamps,
									negot<span class="_ _0"></span>iable </div>
								<div class="t m0 xb h15 y16e ff9 fs1 fc1 sc0 ls0 ws0">instruments in bearer form,
									cashier's cheques, <span class="ffa">travellers’</span> cheques, money orders,
								</div>
								<div class="t m0 xb h15 y16f ff9 fs1 fc1 sc0 ls0 ws0">passports, credit/debit/ATM cards,
									antiques, works <span class="ls2">of</span> <span class="_ _0"></span>art, lottery
									tickets <span class="lsb">and</span> </div>
								<div class="t m0 xb h15 y170 ff9 fs1 fc1 sc0 ls0 ws0">gambling devices, livestock,
									insects,
									animals, human corpses, organs <span class="ls2">or</span> body parts, </div>
								<div class="t m0 xb h15 y171 ff9 fs1 fc1 sc0 ls0 ws0">blood, urine and other liquid
									diagnostic specimens, hazardous <span class="ls2">or</span> bio-medic<span
										class="_ _0"></span>al waste, </div>
								<div class="t m0 xb h15 y172 ff9 fs1 fc1 sc0 ls0 ws0">wet ice, pornographic materials,
									contraband, bottled alcoholic beverages <span class="ls2">or</span> <span
										class="lsb">any</span> </div>
								<div class="t m0 xb h15 y173 ff9 fs1 fc1 sc0 ls0 ws0">intoxicant <span
										class="ls2">or</span>
									narcotics <span class="lsb">and</span> psychotropic substances<span
										class="_ _0"></span>
									<span class="ls2">or</span> <span class="lsb">any</span> other prohibited <span
										class="_ _0"></span>material </div>
								<div class="t m0 xb h15 y174 ff9 fs1 fc1 sc0 ls2 ws0">or<span class="ls0"> material for
										the
										transportation </span>of<span class="ls0"> which <span
											class="_ _0"></span>specific
										authorisation/license is required </span></div>
								<div class="t m0 xb  y12a ff9 fs1 fc1 sc0 ls0 ws0">under applicable laws<span
										class="ff2">)
										(all of such items, the <span class="ff3">“Restricted</span> <span
											class="ff3">Items”</span><span class="ls2">).</span> </span></div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf13" class="pf w0 h0" data-page-no="13">
						<div class="pc pc13 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3 hf y27 ff2 fs1 fc1 sc0 ls0 ws0">3.6.<span class="ff8"> </span>You
									also
									agree that, upon becoming aware of the commission of an offence by You </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0">or Your intention to commit an
									offence
									upon initiating a Pick-up and Drop-off </div>
								<div class="t m0 xb  y14e ff2 fs1 fc1 sc0 ls0 ws0">Service or during a Pick-up and
									Drop-off
									service of any item(s) restricted under </div>
								<div class="t m0 xb  y14f ff3 fs1 fc1 sc0 ls0 ws0">applicable law, the Delivery Partner
									may
									at the Delivery Partner’s sole<span class="_ _1"></span> discretion </div>
								<div class="t m0 xb  y150 ff2 fs1 fc1 sc0 ls0 ws0">choose to take such action as the
									Delivery Partner deems fit including i<span class="_ _1"></span>ntimating law </div>
								<div class="t m0 xb  y151 ff2 fs1 fc1 sc0 ls0 ws0">enforcement authorities about such
									unlawful act<span class="_ _0"></span>ion. </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y153 ff2 fs1 fc1 sc0 ls0 ws0">3.7.<span class="ff8">
									</span>gogo20
									does not check or verify the packages that are being picked up and dropped </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0">off on behalf of You or the Items
									that
									are being delivered to<span class="_ _1"></span> You by the Delivery </div>
								<div class="t m0 xb  y155 ff2 fs1 fc1 sc0 ls0 ws0">Partner, and therefore gogo20 shall
									have
									no liability with respect to the Items or </div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls3 ws0">You<span class="ls0">r use of the
										gogo20
										Services and the Pick Off and Drop Off Servic<span class="_ _1"></span>es.
										However,
										if </span></div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0">it is brought to the knowledge of
									<span class="_ _0"></span>gogo20 through any law enforcement aut<span
										class="_ _1"></span>hority or </div>
								<div class="t m0 xb  y158 ff2 fs1 fc1 sc0 ls0 ws0">any other third-party that You have
									packaged any Restricted Items or availed the </div>
								<div class="t m0 xb  y159 ff2 fs1 fc1 sc0 ls0 ws0">Pick up and Drop Off Services using
									the
									Platform to deliver any Restricted Items, </div>
								<div class="t m0 xb  y15a ff2 fs1 fc1 sc0 ls0 ws0">gogo20 may at its sole discretion
									take
									appropriate actions including sus<span class="_ _1"></span>pension or </div>
								<div class="t m0 xb  y15b ff2 fs1 fc1 sc0 ls0 ws0">termination of Your Account and
									gogo20
									Services. gogo20 may also, on a request </div>
								<div class="t m0 xb  y15c ff2 fs1 fc1 sc0 ls0 ws0">received from the law enforcement
									authority provide requisite details as may be </div>
								<div class="t m0 xb  y15d ff2 fs1 fc1 sc0 ls0 ws0">requested, which may include but not
									be
									limited to details of Your organisation, </div>
								<div class="t m0 xb  y15e ff2 fs1 fc1 sc0 ls0 ws0">Your personal details, transaction
									history, payment details, geo locations, logistics </div>
								<div class="t m0 xb  y15f ff2 fs1 fc1 sc0 ls0 ws0">information, etc to such authorities.
								</div>
								<div class="t m0 xb  y160 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y161 ff2 fs1 fc1 sc0 ls0 ws0">3.8.<span class="ff8"> </span>If a
									transaction initiated by You on the Platform cannot be completed, You shall be
								</div>
								<div class="t m0 xb  y162 ff2 fs1 fc1 sc0 ls0 ws0">notified on the Platform. </div>
								<div class="t m0 xb  y163 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y164 ff2 fs1 fc1 sc0 ls0 ws0">3.9.<span class="ff8">
									</span>Gogo20
									shall use Your location based information that is captured by gogo20 </div>
								<div class="t m0 xb  y165 ff2 fs1 fc1 sc0 ls0 ws0">through a global positioning system
									when
									You are using Your person<span class="_ _0"></span>al computer or </div>
								<div class="t m0 xb  y166 ff2 fs1 fc1 sc0 ls0 ws0">mobile device to request a gogo20
									Service
									on its Platform. Such location based </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">information shall be used by gogo20
									to
									facilitate and improve the gogo20 Services </div>
								<div class="t m0 xb  y168 ff2 fs1 fc1 sc0 ls0 ws0">being offered to You. You acknowledge
									and
									hereby consent to the monit<span class="_ _1"></span>oring and </div>
								<div class="t m0 xb  y169 ff2 fs1 fc1 sc0 ls0 ws0">tracking of Your geo-location
									information. In addition, the Delivery Partner may </div>
								<div class="t m0 xb  y16a ff2 fs1 fc1 sc0 ls0 ws0">have access to such geo-location.
								</div>
								<div class="t m0 x3  y16b ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y16c ff1 fs1 fc1 sc0 ls3 ws0">4.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Vendor/Merchant Information
										</span></span></div>
								<div class="t m0 x3  y16d ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y16e ff2 fs1 fc1 sc0 ls0 ws0">4.1.<span class="ff8"> </span>You
									are
									solely responsible for and in control of the information You provide to us. </div>
								<div class="t m0 xb  y16f ff2 fs1 fc1 sc0 ls0 ws0">Compilation of Vendor/Merchant
									Accounts
									a<span class="ls4">nd </span>Vendor/Merchant Account bearing </div>
								<div class="t m0 xb  y170 ff2 fs1 fc1 sc0 ls0 ws0">contact number and e-mail addresses
									are
									owned by gogo20. </div>
								<div class="t m0 xb  y171 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y172 ff2 fs1 fc1 sc0 ls0 ws0">4.2.<span class="ff8"> </span>In a
									case
									where the Platform is unable to establish a unique identity of the </div>
								<div class="t m0 xb  y173 ff2 fs1 fc1 sc0 ls0 ws0">Vendor/Merchant against the details
									provided by the Vendor/Merchant, the </div>
								<div class="t m0 xb  y174 ff2 fs1 fc1 sc0 ls0 ws0">Account shall be indefinitely
									suspended.
									gogo20 reserves the full discretion to </div>
								<div class="t m0 xb  y12a ff2 fs1 fc1 sc0 ls0 ws0">suspend a <span
										class="ls3">Ve</span>ndor/Merchant's Account in the above event and does not
									have the </div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0">liability to share any Account
									information whatsoever. </div>
								<div class="t m0 x3  y176 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf14" class="pf w0 h0" data-page-no="14">
						<div class="pc pc14 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2 h5 y27 ff1 fs1 fc1 sc0 ls3 ws0">5.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Payment &amp; Taxes </span></span>
								</div>
								<div class="t m0 x3  y14d ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y14e ff1 fs1 fc1 sc0 ls3 ws0">5.1.<span class="ff5 ls0"> <span
											class="_ _1"></span><span class="ff1">Pa<span class="_ _1"></span>yments:
										</span></span></div>
								<div class="t m0 xb  y14f ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y150 ff2 fs1 fc1 sc0 ls0 ws0">5.1.1.<span class="ff8"> <span
											class="_ _2"> </span></span>While initiating a request for a Pick Up and
									Drop
									Off Service, You may be </div>
								<div class="t m0 xc  y151 ff2 fs1 fc1 sc0 ls0 ws0">required to pay a delivery fee to the
									Delivery Partner for availing the Pick <span class="_ _1"></span>Up </div>
								<div class="t m0 xc  y152 ff2 fs1 fc1 sc0 ls0 ws0">and Drop Off Service (<span
										class="ff3">“Delivery</span> <span class="ff3">Fee”</span>), as may be displayed
									to
									You on the </div>
								<div class="t m0 xc  y153 ff2 fs1 fc1 sc0 ls0 ws0">Platform at the time of raising such
									request. gogo20 will facilitate the collection </div>
								<div class="t m0 xc  y154 ff2 fs1 fc1 sc0 ls0 ws0">and disbursement of Delivery Fee for
									the
									Delivery Partner in compliance with </div>
								<div class="t m0 xc  y155 ff2 fs1 fc1 sc0 ls0 ws0">applicable laws. gogo20 shall issue a
									statement of transactions on behalf of the </div>
								<div class="t m0 xc  y156 ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner from time to time.
								</div>
								<div class="t m0 xc  y157 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y158 ff2 fs1 fc1 sc0 ls0 ws0">5.1.2.<span class="ff8"> <span
											class="_ _2"> </span></span>All settlement to the Merchant shall be made in
									compliance with applicable </div>
								<div class="t m0 xc  y159 ff2 fs1 fc1 sc0 ls0 ws0">law. In case the Merchant opts for a
									post-paid payment option, Merchant shall </div>
								<div class="t m0 xc  y15a ff2 fs1 fc1 sc0 ls0 ws0">ensure that the payment towards such
									outstanding amounts are made wi<span class="_ _1"></span>thin </div>
								<div class="t m0 xc  y15b ff2 fs1 fc1 sc0 ls0 ws0">the prescribed time frame and in a
									manner
									as co<span class="_ _1"></span>mmunicated by <span class="_ _0"></span>gogo20 from
								</div>
								<div class="t m0 xc  y15c ff2 fs1 fc1 sc0 ls0 ws0">time to time. </div>
								<div class="t m0 xc  y15d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y15e ff2 fs1 fc1 sc0 ls0 ws0">5.1.3.<span class="ff8"> <span
											class="_ _2"> </span></span>In case, Merchant enables a cash on delivery
									option
									for its customers and </div>
								<div class="t m0 xc  y15f ff2 fs1 fc1 sc0 ls0 ws0">provides instructions/authorisation
									to
									the Deliver<span class="_ _1"></span>y Partners to collect the cash </div>
								<div class="t m0 xc  y160 ff2 fs1 fc1 sc0 ls0 ws0">on behalf of the Merchant at the time
									of
									the drop off, the <span class="_ _1"></span>Merchant shall </div>
								<div class="t m0 xc  y161 ff2 fs1 fc1 sc0 ls0 ws0">ensure that its customers are duly
									notified to hand over the appropriate </div>
								<div class="t m0 xc  y162 ff2 fs1 fc1 sc0 ls0 ws0">amount to the Delivery Partner
									without
									demur or delay. Subject to settlement </div>
								<div class="t m0 xc  y163 ff2 fs1 fc1 sc0 ls0 ws0">of Delivery Fee and any other payment
									obligation adjustment for Mercha<span class="_ _1"></span>nt, </div>
								<div class="t m0 xc  y164 ff2 fs1 fc1 sc0 ls0 ws0">payment of foregoing amount for cash
									on
									delivery shall be made to the </div>
								<div class="t m0 xc  y165 ff2 fs1 fc1 sc0 ls0 ws0">Merchant. </div>
								<div class="t m0 xc  y166 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 h5 y167 ff1 fs1 fc1 sc0 ls3 ws0">5.2.<span class="ff5 ls0"> <span
											class="_ _1"></span><span class="ff1">Taxes: </span></span></div>
								<div class="t m0 xb  y168 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y169 ff2 fs1 fc1 sc0 ls0 ws0">5.2.1.<span class="ff8"> <span
											class="_ _2"> </span></span>You are responsible to comply with the
									applicable
									tax regulations for <span class="_ _1"></span>the </div>
								<div class="t m0 xc  y16a ff2 fs1 fc1 sc0 ls0 ws0">transactions completed using gogo20
									Services including, but not limited to, </div>
								<div class="t m0 xc  y16b ff2 fs1 fc1 sc0 ls0 ws0">compliance with goods and service
									tax,
									wi<span class="_ _1"></span>thholding taxes, if <span class="_ _0"></span>any. You
									agree
									and </div>
								<div class="t m0 xc  y16c ff2 fs1 fc1 sc0 ls0 ws0">acknowledge that any settlement
									amount to
									be paid by Merc<span class="_ _1"></span>hant for Pick up </div>
								<div class="t m0 xc  y16d ff2 fs1 fc1 sc0 ls0 ws0">and Drop off Services shall not be
									subject to deduction of goods and servi<span class="_ _1"></span>ce tax </div>
								<div class="t m0 xc  y16e ff2 fs1 fc1 sc0 ls0 ws0">or withholding taxes. Such settlement
									is
									merely a <span class="_ _1"></span>pass through amount for the </div>
								<div class="t m0 xc  y16f ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner. Any obligation for
									deduction of goods &amp; service tax or </div>
								<div class="t m0 xc  y170 ff2 fs1 fc1 sc0 ls0 ws0">withholding taxes shall be between
									You
									and Delivery Par<span class="_ _1"></span>tner. <span class="_ _0"></span>gogo20
									shall
								</div>
								<div class="t m0 xc  y171 ff2 fs1 fc1 sc0 ls0 ws0">make available the details of Pick up
									and
									Drop off services to You to enable You </div>
								<div class="t m0 xc  y172 ff2 fs1 fc1 sc0 ls0 ws0">to comply with Your tax obligations.
								</div>
								<div class="t m0 xc  y173 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y174 ff2 fs1 fc1 sc0 ls0 ws0">5.2.2.<span class="ff8"> <span
											class="_ _2"> </span></span>You further agree and acknowledge that <span
										class="_ _0"></span>gogo20 shall not be held </div>
								<div class="t m0 xc  y12a ff2 fs1 fc1 sc0 ls0 ws0">responsible/liable for any compliance
									or
									non-compliance of applicable tax laws </div>
								<div class="t m0 xc  y175 ff2 fs1 fc1 sc0 ls0 ws0">by You or the Delivery Partner.
								</div>
								<div class="t m0 x3  y176 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf15" class="pf w0 h0" data-page-no="15">
						<div class="pc pc15 w0 h0"><img class="bi xd y1e2 w6 h16" alt="" src="bg15.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x2 h5 y27 ff1 fs1 fc1 sc0 ls3 ws0">6.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Insurance </span></span></div>
								<div class="t m0 x3 h17 y1e3 ff8 fs4 fc5 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y1e4 ff2 fs1 fc1 sc0 ls0 ws0">You agree and acknowledge that You
									are
									solely r<span class="_ _1"></span>esponsible fo<span class="_ _0"></span>r the items
									that You get </div>
								<div class="t m0 x3  y1e5 ff2 fs1 fc1 sc0 ls0 ws0">delivered using Pick Up and Drop off
									services through the Platform. gogo20 shall in no </div>
								<div class="t m0 x3  y1e6 ff2 fs1 fc1 sc0 ls0 ws0">manner be responsible for any loss,
									theft
									or damage. However, gogo20 may from time </div>
								<div class="t m0 x3  y1e7 ff2 fs1 fc1 sc0 ls0 ws0">to time facilitate Merchant availing
									insurance servic<span class="_ _1"></span>es from th<span class="_ _0"></span>ird
									party
									vendors and </div>
								<div class="t m0 x3  y1e8 ff2 fs1 fc1 sc0 ls0 ws0">Merchant may at its sole discretion
									avail
									such insurance directly from a<span class="_ _1"></span> third<span
										class="_ _0"></span>-party </div>
								<div class="t m0 x3  y1e9 ff2 fs1 fc1 sc0 ls0 ws0">insurance provider. The details of
									such
									insurance are availa<span class="_ _1"></span>ble </div>
								<div class="t m0 x3  y1ea ff2 fs1 fc1 sc0 ls7 ws0">at<span class="ls0">
										https://www.gogo20.com/terms gogo20 disclaims any and all liability for any
										loss,
									</span></div>
								<div class="t m0 x3  y1eb ff2 fs1 fc1 sc0 ls0 ws0">theft or damage caused to the
									Merchant by
									availing the Pick Up and Drop Off Services </div>
								<div class="t m0 x3  y1ec ff2 fs1 fc1 sc0 ls0 ws0">irrespective whether Merchant chooses
									to
									avail<span class="_ _1"></span> an insur<span class="_ _0"></span>ance or not.
								</div>
								<div class="t m0 x3  y1ed ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 yc ff1 fs1 fc1 sc0 ls3 ws0">7.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Rating </span></span></div>
								<div class="t m0 x3  y1ee ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1ef ff2 fs1 fc1 sc0 ls0 ws0">7.1.<span class="ff8"> </span>You
									agree
									that: (i) after completion of a transaction on the Platform, the Platform </div>
								<div class="t m0 xb  y37 ff2 fs1 fc1 sc0 ls0 ws0">will prompt the Merchant with an
									option to
									provi<span class="_ _1"></span>de a rating and comments about </div>
								<div class="t m0 xb  y1f0 ff2 fs1 fc1 sc0 ls0 ws0">the Delivery Partner (with respect to
									the
									services<span class="_ _1"></span> performed by the<span class="_ _0"></span>
									Delivery
								</div>
								<div class="t m0 xb  y1f1 ff2 fs1 fc1 sc0 ls0 ws0">Partner). </div>
								<div class="t m0 xb  y1f2 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1f3 ff2 fs1 fc1 sc0 ls0 ws0">7.2.<span class="ff8">
									</span>gogo20
									and its affiliates reserve the right to use, share and display such ratings and
								</div>
								<div class="t m0 xb  y1f4 ff2 fs1 fc1 sc0 ls0 ws0">comments in any manner in connection
									with
									the business of gogo20 <span class="_ _0"></span>and its </div>
								<div class="t m0 xb  yf7 ff2 fs1 fc1 sc0 ls0 ws0">affiliates without attribution to or
									approval of Merchant and You hereby <span class="_ _1"></span>co<span
										class="_ _0"></span>nsent to </div>
								<div class="t m0 xb  y1f5 ff2 fs1 fc1 sc0 ls0 ws0">the same. gogo20 and its affiliates
									reserve the right to edit or remove comments in </div>
								<div class="t m0 xb  y1f6 ff2 fs1 fc1 sc0 ls0 ws0">the event that such comments include
									obscenities or other objectionable c<span class="_ _1"></span>ontent, </div>
								<div class="t m0 xb  y1f7 ff3 fs1 fc1 sc0 ls0 ws0">include an individual’s name or other
									personal information, or violate<span class="_ _1"></span> any privacy </div>
								<div class="t m0 xb  y1f8 ff2 fs1 fc1 sc0 ls0 ws0">laws, other applicable laws or
									gogo20<span class="ff3">’s or its affiliates’ cont</span>ent policies. </div>
								<div class="t m0 x2 y1f9 ff1 fs0 fc1 sc0 ls0 ws0">Section B </div>
								<div class="t m0 x2  y168 ff2 fs1 fc1 sc0 ls0 ws0">Specific Terms fo<span class="ls7">r
									</span>gogo20 Services </div>
								<div class="t m0 x2 h5 y1fa ff1 fs1 fc1 sc0 ls3 ws0">8.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Cancellation </span></span></div>
								<div class="t m0 x3  y1fb ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y1fc ff2 fs1 fc1 sc0 ls0 ws0">8.1.<span class="ff8"> </span>If
									You
									wish to cancel a transaction on the Platform, You shall select the cancel </div>
								<div class="t m0 xb  y1fd ff2 fs1 fc1 sc0 ls0 ws0">option on the Platform. It is to be
									noted
									that You may not be allowed to cancel a </div>
								<div class="t m0 xb  y1fe ff2 fs1 fc1 sc0 ls0 ws0">transaction initiated on the Platform
									for
									which work the Delivery Partner has </div>
								<div class="t m0 xb  y123 ff2 fs1 fc1 sc0 ls0 ws0">reached the pick-up location. </div>
								<div class="t m0 xb  y1ff ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y200 ff2 fs1 fc1 sc0 ls0 ws0">8.2.<span class="ff8"> </span>The
									transaction initiated by You on the Platform may be cancelled, if: </div>
								<div class="t m0 xb  y201 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y91 ff2 fs1 fc1 sc0 ls0 ws0">8.2.1.<span class="ff8"> <span
											class="_ _2"> </span></span>Information, instructions and authorisations
									provided by You (including the </div>
								<div class="t m0 xc  y202 ff2 fs1 fc1 sc0 ls0 ws0">details of pick up and drop off
									location)
									is not complete or sufficient for </div>
								<div class="t m0 xc  y203 ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner to execute the
									transaction initiated by You. </div>
								<div class="t m0 xc  y204 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y205 ff2 fs1 fc1 sc0 ls0 ws0">8.2.2.<span class="ff8"> <span
											class="_ _2"> </span></span>If a Delivery Partner is not available to
									perform
									th<span class="_ _1"></span>e services,<span class="_ _0"></span> as may be </div>
								<div class="t m0 xc  y10b ff2 fs1 fc1 sc0 ls0 ws0">requested. </div>
							</div><a class="l" href="https://www.gogo20.com/terms">
								<div class="d m1"
									style="border-style:none;position:absolute;left:100.230000px;bottom:630.430000px;width:164.310000px;height:15.800000px;background-color:rgba(255,255,255,0.000001);">
								</div>
							</a>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf16" class="pf w0 h0" data-page-no="16">
						<div class="pc pc16 w0 h0"><img class="bi x7 y206 w3 h18" alt="" src="bg16.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x4 hf y27 ff2 fs1 fc1 sc0 ls0 ws0">8.2.3.<span class="ff8"> <span
											class="_ _2"> </span></span>If the transaction cannot be completed for
									reasons
									not in control of gogo20 </div>
								<div class="t m0 xc  y14d ff2 fs1 fc1 sc0 ls0 ws0">including any technological glitch.
								</div>
								<div class="t m0 x2  y207 ff1 fs1 fc1 sc0 ls0 ws0">Part C </div>
								<div class="t m0 x2  y208 ff2 fs1 fc1 sc0 ls0 ws0">General Terms of Use </div>
								<div class="t m0 x2 h5 y209 ff1 fs1 fc1 sc0 ls3 ws0">9.<span class="ff5 ls0"> <span
											class="_ _2"> </span><span class="ff1">Non-Exclusive </span></span></div>
								<div class="t m0 x3  y20a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y20b ff2 fs1 fc1 sc0 ls0 ws0">gogo20<span class="ff3">’s Services
										shall
										be provided to You on a non<span class="_ _0"></span></span>-exclusive basis.
								</div>
								<div class="t m0 x3  y20c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y20d ff1 fs1 fc1 sc0 ls3 ws0">10.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Eligibility to use </span></span>
								</div>
								<div class="t m0 x3  y20e ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y20f ff2 fs1 fc1 sc0 ls0 ws0">10.1.<span class="ff8"> <span
											class="_ _7"> </span></span>gogo20 reserves the right to refuse access to
									the
									<span class="_ _1"></span>Platform, at any time to </div>
								<div class="t m0 xb  y210 ff2 fs1 fc1 sc0 ls0 ws0">new Merchant or to terminate or
									suspend
									access granted to<span class="_ _1"></span> existing Merchant at </div>
								<div class="t m0 xb  y211 ff2 fs1 fc1 sc0 ls0 ws0">any time without according any
									reasons
									for doing <span class="_ _1"></span>so. </div>
								<div class="t m0 xb  y212 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y213 ff2 fs1 fc1 sc0 ls0 ws0">10.2.<span class="ff8"> <span
											class="_ _7"> </span></span>Unless otherwise permitted by gogo20, You shall
									not
									have more than 1 (one) </div>
								<div class="t m0 xb  y214 ff2 fs1 fc1 sc0 ls0 ws0">active Account (as defined below) on
									the
									Platform. Additionally, You are pr<span class="_ _1"></span>ohibited </div>
								<div class="t m0 xb  y215 ff2 fs1 fc1 sc0 ls0 ws0">from selling, trading, or otherwise
									transferring Your Acco<span class="_ _1"></span>unt to another party or </div>
								<div class="t m0 xb  y216 ff2 fs1 fc1 sc0 ls0 ws0">impersonating any other person for
									the
									purpose of creating an accou<span class="_ _0"></span>nt with the </div>
								<div class="t m0 xb  y217 ff2 fs1 fc1 sc0 ls0 ws0">Platform. </div>
								<div class="t m0 x3  y218 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y219 ff1 fs1 fc1 sc0 ls3 ws0">11.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">User Account, Password and Security
										</span></span></div>
								<div class="t m0 x3  y21a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y21b ff2 fs1 fc1 sc0 ls0 ws0">In order to use the Platform and
									avail
									the gogo20 Services, You will have to register on </div>
								<div class="t m0 x3  y21c ff2 fs1 fc1 sc0 ls0 ws0">the Platform in a manner as contained
									in
									the Clause 2 herein (<span class="ff3">“Account”<span class="_ _0"></span></span>).
									You
									will be </div>
								<div class="t m0 x3  y21d ff2 fs1 fc1 sc0 ls0 ws0">responsible for maintaining the
									confidentiality o<span class="_ _1"></span>f the Account information, and are fully
								</div>
								<div class="t m0 x3  y21e ff2 fs1 fc1 sc0 ls0 ws0">responsible for all activities that
									occur
									under Your Account. You agree to immediately </div>
								<div class="t m0 x3  y21f ff2 fs1 fc1 sc0 ls0 ws0">notify gogo20 of any unauthorized use
									of
									Your Account information or an<span class="_ _0"></span>y other breach </div>
								<div class="t m0 x3  y220 ff2 fs1 fc1 sc0 ls0 ws0">of security. gogo20 cannot and will
									not
									be liable for any loss or damage arising from </div>
								<div class="t m0 x3  y1b1 ff2 fs1 fc1 sc0 ls0 ws0">Your failure to comply with this
									provision. You ma<span class="_ _1"></span>y be held liable for losses incurred by
								</div>
								<div class="t m0 x3  y221 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 or any other visitor to the
									Platform due to authorized or unauthorized use of </div>
								<div class="t m0 x3  y222 ff2 fs1 fc1 sc0 ls0 ws0">Your Account as a result of Your
									failure
									in keeping Your Account in<span class="_ _1"></span>formation secure and </div>
								<div class="t m0 x3  y223 ff3 fs1 fc1 sc0 ls0 ws0">confidential. Use of another
									Merchant’s
									Account information for using t<span class="_ _1"></span>he Platform is </div>
								<div class="t m0 x3  y224 ff2 fs1 fc1 sc0 ls0 ws0">expressly prohibited. </div>
								<div class="t m0 x3  y225 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y226 ff1 fs1 fc1 sc0 ls3 ws0">12.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Confidential Information
										</span></span>
								</div>
								<div class="t m0 x3  y227 ff3 fs1 fc1 sc0 ls0 ws0">“Confid<span class="ff2">ential
									</span>Information”<span class="ff2"> means any confidential, proprietary or other
										non-pu<span class="_ _0"></span>blic </span></div>
								<div class="t m0 x3  y228 ff2 fs1 fc1 sc0 ls0 ws0">information disclosed by one party
									(the
									<span class="ff3">“Discloser”) to the other (the</span> <span
										class="ff3">“Recipient”),
									</span></div>
								<div class="t m0 x3  y229 ff2 fs1 fc1 sc0 ls0 ws0">whether disclosed verbally, in
									writing,
									or by inspe<span class="_ _1"></span>ction of tangible objects. Confidential </div>
								<div class="t m0 x3  ye7 ff2 fs1 fc1 sc0 ls0 ws0">Information will not include that
									information that (a) was previously known to<span class="_ _1"></span> the </div>
								<div class="t m0 x3  y22a ff2 fs1 fc1 sc0 ls0 ws0">Recipient without an obligation of
									confidentiality; <span class="_ _1"></span>(b) was acquired by the Recipient </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf17" class="pf w0 h0" data-page-no="17">
						<div class="pc pc17 w0 h0"><img class="bi x7 y22b w3 h19" alt="" src="bg17.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y22c ff2 fs1 fc1 sc0 ls0 ws0">without any obligation of
									confidentiality
									from a third par<span class="_ _1"></span>ty with the right to make such </div>
								<div class="t m0 x3  y22d ff2 fs1 fc1 sc0 ls4 ws0">di<span class="ls0">sclosure; or (c)
										is
										or bec<span class="_ _1"></span>omes publicly available through no fault of the
										Recipient. Each </span></div>
								<div class="t m0 x3  y22e ff2 fs1 fc1 sc0 ls0 ws0">Recipient agrees that it will not
									disclose to any t<span class="_ _1"></span>hird parties, or use in any way other
								</div>
								<div class="t m0 x3  y22f ff3 fs1 fc1 sc0 ls0 ws0">than as necessary to perform this
									Terms,
									the Discl<span class="_ _1"></span>oser’s Confidential Informa<span
										class="_ _0"></span><span class="ff2">tion. Each </span></div>
								<div class="t m0 x3  y230 ff2 fs1 fc1 sc0 ls0 ws0">Recipient will ensure that
									Confidential
									Information will o<span class="_ _1"></span>nly be made available to those </div>
								<div class="t m0 x3  y231 ff2 fs1 fc1 sc0 ls0 ws0">of its employees and agents who have
									a
									need to know such Confidential<span class="_ _1"></span> Information </div>
								<div class="t m0 x3  y232 ff2 fs1 fc1 sc0 ls0 ws0">and who are be bound by written
									obligations of confidentiality at least as protective of </div>
								<div class="t m0 x3  y233 ff3 fs1 fc1 sc0 ls0 ws0">the Discloser as this Terms before
									such
									individual has access to the Discloser’s </div>
								<div class="t m0 x3  y234 ff2 fs1 fc1 sc0 ls0 ws0">Confidential Information. Each
									Recipient
									will not, and will not authorize ot<span class="_ _1"></span>hers to, </div>
								<div class="t m0 x3  y235 ff2 fs1 fc1 sc0 ls0 ws0">remove, overprint or deface any
									notice of
									copyright, trademark, log<span class="_ _1"></span>o, legen<span
										class="_ _0"></span>d,
									or other </div>
								<div class="t m0 x3  ye ff3 fs1 fc1 sc0 ls0 ws0">notices of ownership from any originals
									or
									copies of the Discloser’s Confid<span class="_ _1"></span>ential </div>
								<div class="t m0 x3  y236 ff2 fs1 fc1 sc0 ls0 ws0">Information. The foregoing
									prohibition on
									disclosure of Confidential Infor<span class="_ _1"></span>mation will not </div>
								<div class="t m0 x3  y237 ff2 fs1 fc1 sc0 ls0 ws0">apply to the extent the Discloser has
									authorized such disclosure,<span class="_ _1"></span> nor to<span
										class="_ _0"></span>
									the extent a </div>
								<div class="t m0 x3  y238 ff2 fs1 fc1 sc0 ls0 ws0">Recipient is required to disclose
									certain
									Confidential Information of t<span class="_ _1"></span>he Discloser as a </div>
								<div class="t m0 x3  y15f ff2 fs1 fc1 sc0 ls0 ws0">legal obligation based on the
									applicable
									laws and regulations or order of a court, </div>
								<div class="t m0 x3  y239 ff2 fs1 fc1 sc0 ls0 ws0">provided that the Recipient gives the
									Discloser prior writ<span class="_ _1"></span>ten notice o<span
										class="_ _0"></span>f
									such obligation to </div>
								<div class="t m0 x3  y23a ff2 fs1 fc1 sc0 ls0 ws0">disclose and reasonably assist in
									filing
									petition of objection etc. prior to m<span class="_ _1"></span>aking such </div>
								<div class="t m0 x3  y23b ff2 fs1 fc1 sc0 ls0 ws0">disclosure. Upon expiration or
									termination of this <span class="_ _1"></span>Terms and as requested by the </div>
								<div class="t m0 x3  y17 ff2 fs1 fc1 sc0 ls0 ws0">Discloser, each Recipient will deliver
									to
									the Discloser (or destroy at <span class="ff3">the Discloser’s </span></div>
								<div class="t m0 x3  y23c ff3 fs1 fc1 sc0 ls0 ws0">election) any and all materials or
									documents containing the Discloser’s Co<span class="_ _1"></span>nfidential </div>
								<div class="t m0 x3  y23d ff2 fs1 fc1 sc0 ls0 ws0">Information, together with all copies
									thereof in whatever form. </div>
								<div class="t m0 x2  y23e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y23f ff1 fs1 fc1 sc0 ls3 ws0">13.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Representations and Warranties
										</span></span></div>
								<div class="t m0 x3  y240 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y241 ff2 fs1 fc1 sc0 ls0 ws0">13.1.<span class="ff8"> <span
											class="_ _7"> </span></span>Each party hereby represents and warrants that:
									(a)
									it has full power and </div>
								<div class="t m0 xb  y242 ff2 fs1 fc1 sc0 ls0 ws0">authority to enter into these Terms
									of
									Use and perform its obligations her<span class="_ _1"></span>eunder; </div>
								<div class="t m0 xb  y243 ff2 fs1 fc1 sc0 ls0 ws0">(b) it is duly organized, validly
									existing and in goo<span class="_ _1"></span>d standing under the laws of the </div>
								<div class="t m0 xb  y244 ff2 fs1 fc1 sc0 ls0 ws0">jurisdiction of its origin; (c) it
									has
									not entered into, and during the Term (as defined </div>
								<div class="t m0 xb  y245 ff2 fs1 fc1 sc0 ls0 ws0">below) will not enter into, any terms
									that would <span class="_ _1"></span>prevent it from complying with or </div>
								<div class="t m0 xb  y246 ff2 fs1 fc1 sc0 ls0 ws0">performing under these Terms of Use
									(in
									your case, including without limi<span class="_ _1"></span>tation, any </div>
								<div class="t m0 xb  y247 ff2 fs1 fc1 sc0 ls0 ws0">exclusive terms with any third
									parties
									for the pick and drop off services via a </div>
								<div class="t m0 xb  y248 ff2 fs1 fc1 sc0 ls0 ws0">technology platform); and (d) the
									content, media and other mater<span class="_ _1"></span>ials used or </div>
								<div class="t m0 xb  y249 ff2 fs1 fc1 sc0 ls0 ws0">provided as part of these Terms of
									Use
									shall not infringe or otherwise<span class="_ _1"></span> violate the </div>
								<div class="t m0 xb  y24a ff2 fs1 fc1 sc0 ls0 ws0">intellectual property rights, rights
									of
									publicity or o<span class="_ _1"></span>ther proprietary right<span
										class="_ _0"></span>s of any third </div>
								<div class="t m0 xb  y24b ff2 fs1 fc1 sc0 ls0 ws0">party. </div>
								<div class="t m0 xb  y24c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf18" class="pf w0 h0" data-page-no="18">
						<div class="pc pc18 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3 hf y27 ff2 fs1 fc1 sc0 ls0 ws0">13.2.<span class="ff8"> <span
											class="_ _7"> </span></span>You agree to use the Platform only: (i) for
									purposes
									that are permitted by </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0">these Terms of Use; and (ii) in
									accordance with any applicable law, regulati<span class="_ _1"></span>on or </div>
								<div class="t m0 xb  y14e ff2 fs1 fc1 sc0 ls0 ws0">generally accepted practices or
									guidelines; (iii) on obtaining and maintaining </div>
								<div class="t m0 xb  y14f ff2 fs1 fc1 sc0 ls0 ws0">throughout the Term any and all valid
									license, approvals, registra<span class="_ _1"></span>tions, no objection </div>
								<div class="t m0 xb  y150 ff2 fs1 fc1 sc0 ls0 ws0">certificates and in compliance with
									any
									law that m<span class="_ _1"></span>ay be specifically applicable to the </div>
								<div class="t m0 xb  y151 ff2 fs1 fc1 sc0 ls0 ws0">business being carried out by
									Merchant
									and/or for use of the Platform<span class="_ _1"></span> or <span
										class="_ _0"></span>gogo20 </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">Services by Merchant. You agree not
									to
									engage in activities that may<span class="_ _1"></span> adversely </div>
								<div class="t m0 xb  y153 ff2 fs1 fc1 sc0 ls0 ws0">affect the use of the Platform by
									gogo20
									or Delivery Partner(s) or other merchants. </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y155 ff2 fs1 fc1 sc0 ls0 ws0">13.3.<span class="ff8"> <span
											class="_ _7"> </span></span>You represent and warrant that You have not
									received
									any notice from any </div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls0 ws0">third party or any governmental
									authority
									and no litigation is pending against You in </div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0">any court of law, which prevents You
									from
									accessing the Platform and/or a<span class="_ _1"></span>vailing </div>
								<div class="t m0 xb  y158 ff2 fs1 fc1 sc0 ls0 ws0">the gogo20 Services. </div>
								<div class="t m0 xb  y159 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y15a ff2 fs1 fc1 sc0 ls0 ws0">13.4.<span class="ff8"> <span
											class="_ _7"> </span></span>You represent and warrant that You are legally
									authorised to<span class="_ _1"></span> view and access </div>
								<div class="t m0 xb  y15b ff2 fs1 fc1 sc0 ls0 ws0">the Platform and avail the gogo20
									Services. </div>
								<div class="t m0 xb  y15c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y15d ff2 fs1 fc1 sc0 ls0 ws0">13.5.<span class="ff8"> <span
											class="_ _7"> </span></span>You agree not to access (or attempt to access)
									the<span class="_ _1"></span> Platform by any means </div>
								<div class="t m0 xb  y15e ff2 fs1 fc1 sc0 ls0 ws0">other than through the interface that
									is
									provided by <span class="_ _0"></span>Gogo20. You shall not use any </div>
								<div class="t m0 xb  y15f ff2 fs1 fc1 sc0 ls0 ws0">deep-link, robot, spider or other
									automatic device, program, algorithm or </div>
								<div class="t m0 xb  y160 ff2 fs1 fc1 sc0 ls0 ws0">methodology, or any similar or
									equivalent
									manual process, to access, acquire, copy </div>
								<div class="t m0 xb  y161 ff2 fs1 fc1 sc0 ls0 ws0">or monitor any portion of the
									Platform,
									or in any way reproduce or circumvent<span class="_ _0"></span> the </div>
								<div class="t m0 xb  y162 ff2 fs1 fc1 sc0 ls0 ws0">navigational structure or
									presentation of
									the Platform, materials or a<span class="_ _1"></span>ny <span
										class="_ _0"></span>Gogo<span class="ls3">20</span> </div>
								<div class="t m0 xb  y163 ff2 fs1 fc1 sc0 ls0 ws0">Property, to obtain or attempt to
									obtain
									any materials, documents or information </div>
								<div class="t m0 xb  y164 ff2 fs1 fc1 sc0 ls0 ws0">through any means not specifically
									made
									available through the Pla<span class="_ _1"></span>tform.<span class="_ _0"></span>
								</div>
								<div class="t m0 xb  y165 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y166 ff2 fs1 fc1 sc0 ls0 ws0">13.6.<span class="ff8"> <span
											class="_ _7"> </span></span>You acknowledge and agree that by accessing or
									using
									the Platform, You <span class="_ _1"></span>may </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">be exposed to content from others
									that
									You may consider offensive, indecent or </div>
								<div class="t m0 xb  y168 ff2 fs1 fc1 sc0 ls0 ws0">otherwise objectionable. gogo20
									disclaims
									all liabilities arising in relation to such </div>
								<div class="t m0 xb  y169 ff2 fs1 fc1 sc0 ls0 ws0">offensive content on the Platform.
								</div>
								<div class="t m0 xb  y16a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y16b ff2 fs1 fc1 sc0 ls0 ws0">13.7.<span class="ff8"> <span
											class="_ _7"> </span></span>Further, you undertake not to: </div>
								<div class="t m0 xb  y16c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y16d ff2 fs1 fc1 sc0 ls0 ws0">13.7.1.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Defame, abuse, harass, threaten or
											otherwise violate the legal rights of </span></span></div>
								<div class="t m0 xc  y16e ff2 fs1 fc1 sc0 ls0 ws0">others; </div>
								<div class="t m0 xc  y16f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y170 ff2 fs1 fc1 sc0 ls0 ws0">13.7.2.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Publish, post, upload, distribute or
											disseminate any inappropriate, profane, </span></span></div>
								<div class="t m0 xc  y171 ff2 fs1 fc1 sc0 ls0 ws0">defamatory, infringing, disparaging,
									ethnically objectionable, obscene, indecent </div>
								<div class="t m0 xc  y172 ff2 fs1 fc1 sc0 ls0 ws0">or unlawful topic, name, material or
									information; </div>
								<div class="t m0 xc  y173 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y174 ff2 fs1 fc1 sc0 ls0 ws0">13.7.3.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Do any such thing that may harms
											minors in
											any way; </span></span></div>
								<div class="t m0 xc  y12a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y175 ff2 fs1 fc1 sc0 ls0 ws0">13.7.4.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Copy, republish, post, display,
											translate,
											transmit, reproduce or distribute any </span></span></div>
								<div class="t m0 xc  y176 ff2 fs1 fc1 sc0 ls0 ws0">Gogo20 Property through any medium
									without obtaining the necessary </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf19" class="pf w0 h0" data-page-no="19">
						<div class="pc pc19 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xc  y27 ff2 fs1 fc1 sc0 ls0 ws0">authorization from gogo20; </div>
								<div class="t m0 xc  y14d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y14e ff2 fs1 fc1 sc0 ls0 ws0">13.7.5.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Conduct or forward surveys, con<span
												class="_ _0"></span>tests, pyramid schemes or chain letters;
										</span></span>
								</div>
								<div class="t m0 xc  y14f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y150 ff2 fs1 fc1 sc0 ls0 ws0">13.7.6.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Upload or distribute files that
											contain
											software or other material protected </span></span></div>
								<div class="t m0 xc  y151 ff2 fs1 fc1 sc0 ls0 ws0">by applicable intellectual property
									laws
									unless You own or control the rights </div>
								<div class="t m0 xc  y152 ff2 fs1 fc1 sc0 ls0 ws0">thereto or have received all
									necessary
									consents; </div>
								<div class="t m0 xc  y153 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y154 ff2 fs1 fc1 sc0 ls0 ws0">13.7.7.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Upload or distribute files or
											documents or
											videos (whether live or pre-</span></span></div>
								<div class="t m0 xc  y155 ff2 fs1 fc1 sc0 ls0 ws0">recorded) that contain viruses,
									corrupted
									files, or any other similar software or </div>
								<div class="t m0 xc  y156 ff2 fs1 fc1 sc0 ls0 ws0">programs that may damage the
									operation of
									the Platform or another's </div>
								<div class="t m0 xc  y157 ff2 fs1 fc1 sc0 ls0 ws0">computer; </div>
								<div class="t m0 xc  y158 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y159 ff2 fs1 fc1 sc0 ls0 ws0">13.7.8.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Engage in any activity that interferes
											with or disrupts access to the Platform </span></span></div>
								<div class="t m0 xc  y15a ff2 fs1 fc1 sc0 ls0 ws0">(or the servers and networks which
									are
									connected to the Platform); </div>
								<div class="t m0 xc  y15b ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y15c ff2 fs1 fc1 sc0 ls0 ws0">13.7.9.<span class="ff8"> <span
											class="_ _5"></span><span class="ff2">Attempt to gain unauthorized access to
											any
											portion or feature of the </span></span></div>
								<div class="t m0 xc  y15d ff2 fs1 fc1 sc0 ls0 ws0">Platform, any other systems or
									networks
									connected to th<span class="_ _0"></span>e Platform, to an<span
										class="_ _1"></span>y
								</div>
								<div class="t m0 xc  y15e ff2 fs1 fc1 sc0 ls0 ws0">gogo20 server, or through the
									Platform,
									by hacking, password mini<span class="_ _1"></span>ng or any </div>
								<div class="t m0 xc  y15f ff2 fs1 fc1 sc0 ls0 ws0">other illegitimate means; </div>
								<div class="t m0 xc  y160 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y161 ff2 fs1 fc1 sc0 ls0 ws0">13.7.10.<span class="ff8"> <span
											class="_ _8"> </span></span>Probe, scan or test the vulnerability of the
									Platform or any network </div>
								<div class="t m0 xc  y162 ff2 fs1 fc1 sc0 ls0 ws0">connected to the Platform, nor breach
									the
									security or authentication measures </div>
								<div class="t m0 xc  y163 ff2 fs1 fc1 sc0 ls0 ws0">on the Platform or any network
									connected
									to the Platform. You may not </div>
								<div class="t m0 xc  y164 ff2 fs1 fc1 sc0 ls0 ws0">reverse look-up, trace or seek to
									trace
									any information on any other User, of or </div>
								<div class="t m0 xc  y165 ff2 fs1 fc1 sc0 ls0 ws0">visitor to, the Platform, to its
									source,
									or exploit the Pla<span class="_ _1"></span>tform or information </div>
								<div class="t m0 xc  y166 ff2 fs1 fc1 sc0 ls0 ws0">made available or offered by or
									through
									the Platform, in any way whether<span class="_ _1"></span> or </div>
								<div class="t m0 xc  y167 ff2 fs1 fc1 sc0 ls0 ws0">not the purpose is to reveal any
									information, including but not limite<span class="_ _1"></span>d to </div>
								<div class="t m0 xc  y168 ff2 fs1 fc1 sc0 ls0 ws0">personal identification information,
									other than Your own information, as </div>
								<div class="t m0 xc  y169 ff2 fs1 fc1 sc0 ls0 ws0">provided on the Platform; </div>
								<div class="t m0 xc  y16a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y16b ff2 fs1 fc1 sc0 ls0 ws0">13.7.11.<span class="ff8"> <span
											class="_ _8"> </span></span>Disrupt or interfere with the security of, or
									otherwise cause harm to, </div>
								<div class="t m0 xc  y16c ff2 fs1 fc1 sc0 ls0 ws0">the Platform, systems resources,
									accounts, passwords, servers or networks </div>
								<div class="t m0 xc  y16d ff2 fs1 fc1 sc0 ls0 ws0">connected to or accessible through
									the
									Platform or any affiliated or linked sites; </div>
								<div class="t m0 xc  y16e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y16f ff2 fs1 fc1 sc0 ls0 ws0">13.7.12.<span class="ff8"> <span
											class="_ _8"> </span></span>Collect or store data about other user,
									merchant,
									Delivery Partner in </div>
								<div class="t m0 xc  y170 ff2 fs1 fc1 sc0 ls0 ws0">connection with the prohibited
									conduct
									and activities set forth herei<span class="_ _1"></span>n;<span class="_ _0"></span>
								</div>
								<div class="t m0 xc  y171 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y172 ff2 fs1 fc1 sc0 ls0 ws0">13.7.13.<span class="ff8"> <span
											class="_ _8"> </span></span>Use any device or software to interfere or
									attempt
									to interfere with </div>
								<div class="t m0 xc  y173 ff2 fs1 fc1 sc0 ls0 ws0">the proper working of the Platform or
									any
									transa<span class="_ _1"></span>ction being conduct<span class="_ _0"></span>ed on
									the
								</div>
								<div class="t m0 xc  y174 ff3 fs1 fc1 sc0 ls0 ws0">Platform, or with any other person’s
									use
									of the Pl<span class="_ _1"></span>atform;<span class="ff2"> </span></div>
								<div class="t m0 xc  y12a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y175 ff2 fs1 fc1 sc0 ls0 ws0">13.7.14.<span class="ff8"> <span
											class="_ _8"> </span></span>Use the Platform or any material or Gogo20
									Property
									for any purpose </div>
								<div class="t m0 xc  y176 ff2 fs1 fc1 sc0 ls0 ws0">that is unlawful or prohibited by
									these
									Terms of Use, or to solicit the </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf1a" class="pf w0 h0" data-page-no="1a">
						<div class="pc pc1a w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xc  y27 ff2 fs1 fc1 sc0 ls0 ws0">performance of any illegal activity or
									other activity which infringes the rights of </div>
								<div class="t m0 xc  y14d ff2 fs1 fc1 sc0 ls0 ws0">the Company or other third parties;
								</div>
								<div class="t m0 xc  y14e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y14f ff2 fs1 fc1 sc0 ls0 ws0">13.7.15.<span class="ff8"> <span
											class="_ _8"> </span></span>Falsify or delete any author attributions, legal
									or
									other proper notices </div>
								<div class="t m0 xc  y150 ff2 fs1 fc1 sc0 ls0 ws0">or proprietary designations or labels
									of
									the origin or source of software or other </div>
								<div class="t m0 xc  y151 ff2 fs1 fc1 sc0 ls0 ws0">material contained in a file that is
									uploaded; </div>
								<div class="t m0 xc  y152 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y153 ff2 fs1 fc1 sc0 ls0 ws0">13.7.16.<span class="ff8"> <span
											class="_ _8"> </span></span>Impersonate any other user, Delivery Partner or
									person; </div>
								<div class="t m0 xc  y154 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y155 ff2 fs1 fc1 sc0 ls0 ws0">13.7.17.<span class="ff8"> <span
											class="_ _8"> </span></span>Violate any applicable laws or regulations for
									the
									<span class="_ _1"></span>time being in f<span class="_ _0"></span>orce </div>
								<div class="t m0 xc  y156 ff2 fs1 fc1 sc0 ls0 ws0">within or outside Nepal <span
										class="ff3">or anyone’s right to privacy or personality;</span> </div>
								<div class="t m0 xc  y157 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y158 ff2 fs1 fc1 sc0 ls0 ws0">13.7.18.<span class="ff8"> <span
											class="_ _8"> </span></span>Violate these Terms of Use contained herein or
									elsewhere; </div>
								<div class="t m0 xc  y159 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y15a ff2 fs1 fc1 sc0 ls0 ws0">13.7.19.<span class="ff8"> <span
											class="_ _8"> </span></span>Threatens the unity, integrity, defence,
									security or
									sovereignty of </div>
								<div class="t m0 xc  y15b ff2 fs1 fc1 sc0 ls0 ws0">Nepal, friendly relation with foreign
									states, or public order or causes incitement </div>
								<div class="t m0 xc  y15c ff2 fs1 fc1 sc0 ls0 ws0">to the commission of any cognisable
									offence or prevents investigation of any </div>
								<div class="t m0 xc  y15d ff2 fs1 fc1 sc0 ls0 ws0">offence or is insulting for any other
									nation; and </div>
								<div class="t m0 xc  y15e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y15f ff2 fs1 fc1 sc0 ls0 ws0">13.7.20.<span class="ff8"> <span
											class="_ _8"> </span></span>Reverse engineer, modify, copy, distribute,
									transmit, display, perform,<span class="_ _1"></span> </div>
								<div class="t m0 xc  y160 ff2 fs1 fc1 sc0 ls0 ws0">reproduce, publish, license, create
									derivative works from, transfer,<span class="_ _1"></span> or sell any </div>
								<div class="t m0 xc  y161 ff2 fs1 fc1 sc0 ls0 ws0">information or software obtained from
									the
									Platform. </div>
								<div class="t m0 xb  y162 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y163 ff2 fs1 fc1 sc0 ls0 ws0">13.8.<span class="ff8"> <span
											class="_ _7"> </span></span>You agree and acknowledge that the use of the
									Gogo20
									Services offered by </div>
								<div class="t m0 xb  y164 ff2 fs1 fc1 sc0 ls0 ws0">Gogo20 is at Your sole risk and that
									Gogo20 disclaims all representations and </div>
								<div class="t m0 xb  y165 ff2 fs1 fc1 sc0 ls0 ws0">warranties of any kind, whether
									express
									or implied as to condition, suitabilit<span class="_ _0"></span>y, </div>
								<div class="t m0 xb  y166 ff2 fs1 fc1 sc0 ls0 ws0">quality, merchantability and fitness
									for
									any purposes are exc<span class="_ _1"></span>luded to the fullest </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">extent permitted by law. </div>
								<div class="t m0 xb  y168 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y169 ff2 fs1 fc1 sc0 ls0 ws0">13.9.<span class="ff8"> <span
											class="_ _7"> </span></span>All materials/content on our Platform (except
									any
									third-party content </div>
								<div class="t m0 xb  y16a ff2 fs1 fc1 sc0 ls0 ws0">available on the Platform),
									including,
									without limitation, names, logos, trademarks, </div>
								<div class="t m0 xb  y16b ff2 fs1 fc1 sc0 ls0 ws0">images, text, columns, graphics,
									videos,
									photographs, illustrations, ar<span class="_ _1"></span>twork, </div>
								<div class="t m0 xb  y16c ff2 fs1 fc1 sc0 ls0 ws0">software and other elements
									(collectively, <span class="ff3">“Material”</span>) are protected by copyrights,
								</div>
								<div class="t m0 xb  y16d ff2 fs1 fc1 sc0 ls0 ws0">trademarks and/or other intellectual
									property rights owned<span class="_ _1"></span> and controlled by </div>
								<div class="t m0 xb  y16e ff2 fs1 fc1 sc0 ls0 ws0">Gogo20. You acknowledge and agree
									that
									the Material is made available<span class="_ _1"></span> for limited, </div>
								<div class="t m0 xb  y16f ff2 fs1 fc1 sc0 ls0 ws0">non-commercial, personal use only.
									Except
									as specifically provided herein <span class="_ _1"></span>or </div>
								<div class="t m0 xb  y170 ff2 fs1 fc1 sc0 ls0 ws0">elsewhere in our Platform, no
									Material
									may be copied, reproduced, repu<span class="_ _1"></span>blished, </div>
								<div class="t m0 xb  y171 ff2 fs1 fc1 sc0 ls0 ws0">sold, downloaded, posted,
									transmitted, or
									distributed in any way, or other<span class="_ _1"></span>wise used </div>
								<div class="t m0 xb  y172 ff2 fs1 fc1 sc0 ls0 ws0">for any purpose other than the
									purposes
									stated under these Terms of Use,<span class="_ _1"></span> by any </div>
								<div class="t m0 xb  y173 ff2 fs1 fc1 sc0 ls0 ws0">person or entity, without Gogo20<span
										class="ff3">’s prior express written permission. You may not </span></div>
								<div class="t m0 xb  y174 ff2 fs1 fc1 sc0 ls0 ws0">add, delete, distort, or otherwise
									modify
									the Material. Any unauthorized attempt to </div>
								<div class="t m0 xb  y12a ff2 fs1 fc1 sc0 ls0 ws0">modify any Material, to defeat or
									circumvent any security features, or to utilize our </div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0">Platform or any part of the Material
									for
									any purpose other than its intended </div>
								<div class="t m0 xb  y176 ff2 fs1 fc1 sc0 ls0 ws0">purposes is strictly prohibited.
									Subject
									to the above restrictions under this Clause, </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf1b" class="pf w0 h0" data-page-no="1b">
						<div class="pc pc1b w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xb  y27 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 hereby grants You a
									non-exclusive,
									freely revocable (upon notice from </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0">gogo20), non-transferable access to
									view
									the Material on the Platform. </div>
								<div class="t m0 xb  y14e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y14f ff1 fs1 fc1 sc0 ls3 ws0">14.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Intellectual Property Rights
										</span></span></div>
								<div class="t m0 x3  y150 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y151 ff2 fs1 fc1 sc0 ls0 ws0">14.1.<span class="ff8"> <span
											class="_ _7"> </span></span>The Platform and process, and their selection
									and
									arrangement, including but </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">not limited to, all text, videos,
									graphics, user interfaces, visual interfaces, s<span class="_ _1"></span>ounds
								</div>
								<div class="t m0 xb  y153 ff2 fs1 fc1 sc0 ls0 ws0">and music (if any), artwork,
									algorithm
									and computer code (and any com<span class="_ _1"></span>bination </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0">thereof), except any third party
									software
									available on the Platform, is owned by </div>
								<div class="t m0 xb  y155 ff2 fs1 fc1 sc0 ls0 ws0">Gogo20 (<span
										class="ff3">“</span>gogo20
									<span class="ff3">Property”</span>) and the design, structure, selection,
									coordinat<span class="_ _1"></span>ion, </div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls0 ws0">expression, look and feel and
									arrangement
									of suc<span class="_ _1"></span>h <span class="_ _0"></span>gogo20 Property is
									protected
									by </div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0">copyright, patent and trademark laws,
									and
									various other intellectual property rights. </div>
								<div class="t m0 xb  y158 ff2 fs1 fc1 sc0 ls0 ws0">You are not permitted to use gogo20
									Property without the prior written consent of </div>
								<div class="t m0 xb  y159 ff2 fs1 fc1 sc0 ls0 ws0">gogo20. </div>
								<div class="t m0 xb  y15a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y15b ff2 fs1 fc1 sc0 ls0 ws0">14.2.<span class="ff8"> <span
											class="_ _7"> </span></span>The trademarks, logos and service marks
									display<span class="_ _1"></span>ed on the Platform <span class="_ _0"></span>(<span
										class="ff3">“Marks”</span><span class="ls2">) </span></div>
								<div class="t m0 xb  y15c ff2 fs1 fc1 sc0 ls0 ws0">are the property of gogo20, except
									any
									trademark, logos and service marks of third </div>
								<div class="t m0 xb  y15d ff2 fs1 fc1 sc0 ls0 ws0">parties available on the Platform.
									You
									are not pe<span class="_ _1"></span>rmitted to use the Marks without </div>
								<div class="t m0 xb  y15e ff2 fs1 fc1 sc0 ls0 ws0">the prior consent of gogo20 or such
									third
									party as may be applicable. </div>
								<div class="t m0 x3  y15f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y160 ff1 fs1 fc1 sc0 ls3 ws0">15.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Disclaimer of Warranties and
											Liabilities
										</span></span></div>
								<div class="t m0 x3  y161 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3  y162 ff2 fs1 fc1 sc0 ls0 ws0">You expressly understand and agree
									that,
									to the maximum extent permitted by </div>
								<div class="t m0 x3  y163 ff2 fs1 fc1 sc0 ls0 ws0">applicable law: </div>
								<div class="t m0 x3  y164 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y165 ff2 fs1 fc1 sc0 ls0 ws0">15.1.<span class="ff8"> <span
											class="_ _7"> </span></span>The Platform, gogo20 Property and gogo20
									Services
									are provided by gogo20 </div>
								<div class="t m0 xb  y166 ff3 fs1 fc1 sc0 ls0 ws0">on an “as is” basis without warranty
									of
									any kind, express, implied, sta<span class="_ _1"></span>tutory or </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">otherwise, including the implied
									warranties of title, n<span class="lsc">on<span
											class="_ _0"></span></span>-infringement, </div>
								<div class="t m0 xb  y168 ff2 fs1 fc1 sc0 ls0 ws0">merchantability or fitness for a
									particular purpose. Without limiting the for<span class="_ _1"></span>egoing, </div>
								<div class="t m0 xb  y169 ff2 fs1 fc1 sc0 ls0 ws0">Gogo20 makes no warranty that (i) the
									Platform, Gogo20 Services will meet Your </div>
								<div class="t m0 xb  y16a ff2 fs1 fc1 sc0 ls0 ws0">requirements or Your use of the
									Platform
									will be uninterrupted, timely, secure or </div>
								<div class="t m0 xb  y16b ff2 fs1 fc1 sc0 ls0 ws0">error-free; (ii) the quality of the
									Platform will mee<span class="_ _1"></span>t Your expectations; or (iii) any </div>
								<div class="t m0 xb  y16c ff2 fs1 fc1 sc0 ls0 ws0">errors or defects in the Platform
									will be
									correcte<span class="_ _1"></span>d. No advice or information, </div>
								<div class="t m0 xb  y16d ff2 fs1 fc1 sc0 ls0 ws0">whether oral or written, obtained by
									You
									from <span class="_ _0"></span>Gogo20 shall create any warranty </div>
								<div class="t m0 xb  y16e ff2 fs1 fc1 sc0 ls0 ws0">not expressly stated in these Terms
									of
									Use. </div>
								<div class="t m0 xb  y16f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y170 ff2 fs1 fc1 sc0 ls0 ws0">15.2.<span class="ff8"> <span
											class="_ _7"> </span></span>gogo20 will have no liability related to any
									Merchant content arising unde<span class="_ _1"></span>r </div>
								<div class="t m0 xb  y171 ff2 fs1 fc1 sc0 ls0 ws0">intellectual property rights, libel,
									privacy, publicity, obsceni<span class="_ _1"></span>ty or other laws. <span
										class="_ _0"></span>gogo20 </div>
								<div class="t m0 xb  y172 ff2 fs1 fc1 sc0 ls0 ws0">also disclaims all liability with
									respect
									to the misuse, loss, modification or </div>
								<div class="t m0 xb  y173 ff2 fs1 fc1 sc0 ls0 ws0">unavailability of any Merchant
									content.
								</div>
								<div class="t m0 xb  y174 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y12a ff2 fs1 fc1 sc0 ls0 ws0">15.3.<span class="ff8"> <span
											class="_ _7"> </span></span>gogo20 will not be liable for any loss that You
									may
									incur as a consequence of </div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0">unauthorized use of Your Account or
									Account information in connection <span class="_ _1"></span>with t<span
										class="_ _0"></span>he </div>
								<div class="t m0 xb  y176 ff2 fs1 fc1 sc0 ls0 ws0">Platform either with or without Your
									knowledge. </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf1c" class="pf w0 h0" data-page-no="1c">
						<div class="pc pc1c w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3 hf y27 ff2 fs1 fc1 sc0 ls0 ws0">15.4.<span class="ff8"> <span
											class="_ _7"> </span></span>gogo20 shall not be responsible for the delay or
									inability to use the Platfo<span class="_ _1"></span>rm, </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0">gogo20 Services or related
									functionalities, the provision of or <span class="_ _1"></span>failure to provide
								</div>
								<div class="t m0 xb  y14e ff2 fs1 fc1 sc0 ls0 ws0">functionalities, or for any
									information,
									software, functionalities and related graphics </div>
								<div class="t m0 xb  y14f ff2 fs1 fc1 sc0 ls0 ws0">obtained through the Platform, or
									otherwise arisi<span class="_ _1"></span>ng out of the use of the Platform, </div>
								<div class="t m0 xb  y150 ff2 fs1 fc1 sc0 ls0 ws0">whether based on contract, tort,
									negligence, strict<span class="_ _1"></span> liability or otherwise. Further, </div>
								<div class="t m0 xb  y151 ff2 fs1 fc1 sc0 ls0 ws0">Gogo20 shall not be held responsible
									for
									non-availability of the Platform during </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">periodic maintenance operations or
									any
									unplanned suspension of access to the </div>
								<div class="t m0 xb  y153 ff2 fs1 fc1 sc0 ls0 ws0">Platform that may occur due to
									technical
									reasons or for a<span class="_ _1"></span>ny reason beyond </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0">Gogo20's control. You understand and
									agree that any material or data downloaded </div>
								<div class="t m0 xb  y155 ff2 fs1 fc1 sc0 ls0 ws0">or otherwise obtained through the
									Platform is done entirely at Your own discretion </div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls0 ws0">and risk, and that You will be solely
									responsible for any damage t<span class="_ _1"></span>o Your computer </div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0">systems or loss of data that results
									from
									the download of such material or data. </div>
								<div class="t m0 xb  y158 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y159 ff2 fs1 fc1 sc0 ls0 ws0">15.5.<span class="ff8"> <span
											class="_ _7"> </span></span>gogo20 shall not be liable for any damages,
									loss,
									cost, expense of any kind </div>
								<div class="t m0 xb  y15a ff2 fs1 fc1 sc0 ls0 ws0">arising from Your use of the Platform
									or
									gogo20 Services, including, but not limited </div>
								<div class="t m0 xb  y15b ff2 fs1 fc1 sc0 ls0 ws0">to direct, indirect, incidental,
									punitive, and conse<span class="_ _1"></span>quential damages.<span
										class="_ _0"></span>
								</div>
								<div class="t m0 x3  y15c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y15d ff1 fs1 fc1 sc0 ls3 ws0">16.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Indemnification and Limitation of
											Liability </span></span></div>
								<div class="t m0 x3  y15e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y15f ff2 fs1 fc1 sc0 ls0 ws0">16.1.<span class="ff8"> <span
											class="_ _7"> </span></span><span class="ls3">You</span> agree to indemnify,
									defend and hold harmles<span class="_ _1"></span>s gogo<span class="_ _0"></span>20
									and
									its affiliates </div>
								<div class="t m0 xb  y160 ff2 fs1 fc1 sc0 ls0 ws0">including but not limited to its
									officers, directors, consultants, agents and </div>
								<div class="t m0 xb  y161 ff2 fs1 fc1 sc0 ls0 ws0">employees (<span
										class="ff3">“Indemnitees”</span>) from and against any and all losses,
									liabilities,
									claims, </div>
								<div class="t m0 xb  y162 ff2 fs1 fc1 sc0 ls0 ws0">damages, demands, costs and expenses
									(including legal fees and disbursements in </div>
								<div class="t m0 xb  y163 ff2 fs1 fc1 sc0 ls0 ws0">connection therewith and interest
									chargeable <span class="_ _1"></span>thereon) asserted against or incurred </div>
								<div class="t m0 xb  y164 ff2 fs1 fc1 sc0 ls0 ws0">by the Indemnitees that arise out of,
									result from, <span class="_ _1"></span>or may be payable by virtue of,<span
										class="_ _0"></span> </div>
								<div class="t m0 xb  y165 ff2 fs1 fc1 sc0 ls0 ws0">any breach or non-performance of any
									obligation, covenant, representation or </div>
								<div class="t m0 xb  y166 ff2 fs1 fc1 sc0 ls0 ws0">warranty by You pursuant to these
									Terms
									of Use. Further, You agree to hol<span class="_ _1"></span>d the </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">Indemnitees harmless against any
									claims
									made by any third <span class="_ _1"></span>party due to, or arising </div>
								<div class="t m0 xb  y168 ff2 fs1 fc1 sc0 ls0 ws0">out of, or in connection with, Your
									use
									of the Platform, Gogo20 Services, any </div>
								<div class="t m0 xb  y169 ff2 fs1 fc1 sc0 ls0 ws0">misrepresentation with respect to the
									data or information provided by Yo<span class="_ _1"></span>u in </div>
								<div class="t m0 xb  y16a ff2 fs1 fc1 sc0 ls0 ws0">relation to the Account, Your
									violation
									of these T<span class="_ _1"></span>erms of Use, or Your violation of </div>
								<div class="t m0 xb  y16b ff2 fs1 fc1 sc0 ls0 ws0">any rights of another, including any
									intellectual property rights. </div>
								<div class="t m0 xb  y16c ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y16d ff2 fs1 fc1 sc0 ls0 ws0">16.2.<span class="ff8"> <span
											class="_ _7"> </span></span>In no event shall the Indemnitees, be liable to
									You
									or any third <span class="_ _1"></span>party for any </div>
								<div class="t m0 xb  y16e ff2 fs1 fc1 sc0 ls0 ws0">special, incidental, indirect,
									consequential or puni<span class="_ _1"></span>tive damages whatsoever, arising
								</div>
								<div class="t m0 xb  y16f ff2 fs1 fc1 sc0 ls0 ws0">out of or in connection with Your use
									of
									or access to the Platform or <span class="_ _0"></span>gogo20 </div>
								<div class="t m0 xb  y170 ff2 fs1 fc1 sc0 ls0 ws0">Property or gogo20 Services on the
									Platform. </div>
								<div class="t m0 xb  y171 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y172 ff2 fs1 fc1 sc0 ls0 ws0">16.3.<span class="ff8"> <span
											class="_ _7"> </span></span>Your indemnification obligation under the Terms
									of
									Use will survive the </div>
								<div class="t m0 xb  y173 ff2 fs1 fc1 sc0 ls0 ws0">termination of Your Account or use of
									the
									Platform or gogo20 Services. </div>
								<div class="t m0 xb  y174 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y12a ff2 fs1 fc1 sc0 ls0 ws0">16.4.<span class="ff8"> <span
											class="_ _7"> </span></span>Subject to applicable laws, in no event will
									gogo20
									or its employees </div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0">aggregate liability, arising from or
									related to the gogo20 Services or the use of the </div>
								<div class="t m0 xb  y176 ff2 fs1 fc1 sc0 ls0 ws0">Platform shall not exceed for any and
									all
									causes of actions brought by You or on </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf1d" class="pf w0 h0" data-page-no="1d">
						<div class="pc pc1d w0 h0"><img class="bi x7 y24d w3 h1a" alt="" src="bg1d.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xb  y27 ff2 fs1 fc1 sc0 ls0 ws0">behalf of You. </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y14e ff2 fs1 fc1 sc0 ls0 ws0">16.5.<span class="ff8"> <span
											class="_ _7"> </span></span>The Platform and the gogo20 Services are only
									available to merchants located </div>
								<div class="t m0 xb  y14f ff2 fs1 fc1 sc0 ls0 ws0">in Nepal. Merchant shall not access
									or
									use the Platform from any other juri<span class="_ _1"></span>sdiction </div>
								<div class="t m0 xb  y150 ff2 fs1 fc1 sc0 ls0 ws0">except for Nepal. If a Merchant
									accesses
									or uses the Platform from any other </div>
								<div class="t m0 xb  y151 ff2 fs1 fc1 sc0 ls0 ws0">jurisdiction except for Nepal, the
									Merchant shall be <span class="_ _0"></span>liable to comply with all </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">applicable laws and <span
										class="lsd">Go<span class="_ _0"></span></span>go20 shall not be liable for the
									same, whatsoever. </div>
								<div class="t m0 x3  y153 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y154 ff1 fs1 fc1 sc0 ls3 ws0">17.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Violation of the Terms of Use
										</span></span></div>
								<div class="t m0 x3  y24e ff2 fs1 fc1 sc0 ls0 ws0">You agree that any violation by You
									of
									these Term<span class="_ _1"></span>s of Use will constitute an unlawful </div>
								<div class="t m0 x3  y24f ff2 fs1 fc1 sc0 ls0 ws0">and unfair business practice, and
									will
									cause irreparable harm to gogo20, for which </div>
								<div class="t m0 x3  y250 ff2 fs1 fc1 sc0 ls0 ws0">monetary damages would be inadequate,
									and
									You consent to the <span class="_ _0"></span>gogo20 obtaining any </div>
								<div class="t m0 x3  y251 ff2 fs1 fc1 sc0 ls0 ws0">injunctive or equitable relief that
									they
									deem nece<span class="_ _1"></span>ssary or appropriate in such </div>
								<div class="t m0 x3  y252 ff2 fs1 fc1 sc0 ls0 ws0">circumstances. These remedies are in
									addition to any other remedies that the Gogo20 </div>
								<div class="t m0 x3  y253 ff2 fs1 fc1 sc0 ls0 ws0">may have at law or in equity. If
									gogo20
									takes any legal action against You as a result of </div>
								<div class="t m0 x3  y254 ff2 fs1 fc1 sc0 ls0 ws0">Your violation of these Terms of Use,
									gogo20 will be entitled to recover fr<span class="_ _1"></span>om You, and </div>
								<div class="t m0 x3  y255 ff2 fs1 fc1 sc0 ls0 ws0">You agree to pa<span class="ff3">y
										all
										reasonable attorneys’ fees a</span>nd costs of such action, in addition to
								</div>
								<div class="t m0 x3  y256 ff2 fs1 fc1 sc0 ls0 ws0">any other relief that may be
									granted.<span class="fc5"> </span></div>
								<div class="t m0 x2 h5 y257 ff1 fs1 fc1 sc0 ls3 ws0">18.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Additional Terms </span></span></div>
								<div class="t m0 x3  y258 ff2 fs1 fc1 sc0 ls0 ws0">We may also require You to follow
									additional rules, guidelines or other co<span class="_ _1"></span>nditions in </div>
								<div class="t m0 x3  y259 ff2 fs1 fc1 sc0 ls0 ws0">order to participate in certain
									promotions or activities available through the Platform. </div>
								<div class="t m0 x3  y25a ff2 fs1 fc1 sc0 ls0 ws0">These additional terms shall form a
									part
									of these Terms of Use, and You agree to comply </div>
								<div class="t m0 x3  y25b ff2 fs1 fc1 sc0 ls0 ws0">with them when You participate in
									those
									promotions, or otherwise engag<span class="_ _1"></span>e in activities </div>
								<div class="t m0 x3  y43 ff2 fs1 fc1 sc0 ls0 ws0">governed by such additional terms.
								</div>
								<div class="t m0 x2 h5 y25c ff1 fs1 fc1 sc0 ls3 ws0">19.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Link to third-parties </span></span>
								</div>
								<div class="t m0 x3  y25d ff2 fs1 fc1 sc0 ls0 ws0">The Platform may contain links to
									other
									sites owned by third parties (i.e. a<span class="_ _1"></span>dvertisers, </div>
								<div class="t m0 x3  y25e ff2 fs1 fc1 sc0 ls0 ws0">affiliate partners, strategic
									partners,
									or others). gogo20 shall not be responsible for </div>
								<div class="t m0 x3  y25f ff2 fs1 fc1 sc0 ls0 ws0">examining or evaluating such
									third-party
									websites, and gogo20 does not warrant the </div>
								<div class="t m0 x3  y260 ff2 fs1 fc1 sc0 ls4 ws0">pr<span class="ls0">oducts or
										offerings
										of, any of these<span class="_ _1"></span> businesses or individuals, or the
										accuracy of the </span></div>
								<div class="t m0 x3  y261 ff2 fs1 fc1 sc0 ls0 ws0">content of such thir<span
										class="lse">d
									</span>-<span class="_ _0"></span> party websites. gogo20 does not assume any
									responsibility<span class="_ _1"></span> or </div>
								<div class="t m0 x3  y262 ff2 fs1 fc1 sc0 ls0 ws0">liability for the actions, product,
									and
									content of any such third-party websites. Befor<span class="ls1">e </span></div>
								<div class="t m0 x3  ye8 ff2 fs1 fc1 sc0 ls0 ws0">You use/access any such third-party
									websites, you should review the applicable terms of </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf1e" class="pf w0 h0" data-page-no="1e">
						<div class="pc pc1e w0 h0"><img class="bi x7 y50 w3 h1b" alt="" src="bg1e.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y22c ff2 fs1 fc1 sc0 ls0 ws0">use and policies for such third-party
									websites. If You decide to access any such linked </div>
								<div class="t m0 x3  y22d ff2 fs1 fc1 sc0 ls0 ws0">third party website, you do so at
									Your
									own risk. </div>
								<div class="t m0 x2 h5 y263 ff1 fs1 fc1 sc0 ls3 ws0">20.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Term and Termination </span></span>
								</div>
								<div class="t m0 x3  y264 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y265 ff2 fs1 fc1 sc0 ls0 ws0">20.1.<span class="ff8"> <span
											class="_ _7"> </span></span>These Terms of Use will continue to apply until
									terminated by either You <span class="_ _1"></span>or </div>
								<div class="t m0 xb  y266 ff2 fs1 fc1 sc0 ls0 ws0">gogo20 as set forth below. If You
									object
									to these Terms of Use or are dissa<span class="_ _1"></span>tisfied </div>
								<div class="t m0 xb  y267 ff2 fs1 fc1 sc0 ls0 ws0">with the Platform, gogo20 Services,
									your
									only recourse, subject to the clearance of </div>
								<div class="t m0 xb  y268 ff2 fs1 fc1 sc0 ls0 ws0">all payment obligations either to
									gogo20
									or the Delivery Partner, is to terminate </div>
								<div class="t m0 xb  y269 ff3 fs1 fc1 sc0 ls0 ws0">Your Account on the Platform by
									giving a
									15 days’ advance written no<span class="_ _1"></span>tice to Us. </div>
								<div class="t m0 xb  y26a ff2 fs1 fc1 sc0 ls0 ws0">Gogo20 will make Your account dormant
									upon receipt of request in writing and </div>
								<div class="t m0 xb  y26b ff2 fs1 fc1 sc0 ls0 ws0">payment of outstanding dues, if any.
									Even
									after your account with gogo<span class="_ _0"></span>20 is </div>
								<div class="t m0 xb  yf1 ff2 fs1 fc1 sc0 ls0 ws0">disabled, dormant or made inactive,
									the
									terms agreed by You at th<span class="_ _1"></span>e time of </div>
								<div class="t m0 xb  y26c ff2 fs1 fc1 sc0 ls0 ws0">registration will remain in effect.
									This
									termination shall be e<span class="_ _1"></span>ffective only once You </div>
								<div class="t m0 xb  y26d ff2 fs1 fc1 sc0 ls0 ws0">have cleared all Your dues that You
									are
									liable to pay as per these Terms of Use. </div>
								<div class="t m0 xb  y26e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y26f ff2 fs1 fc1 sc0 ls0 ws0">20.2.<span class="ff8"> <span
											class="_ _7"> </span></span>The Company may terminate Your future access to
									the
									Platform or suspen<span class="_ _1"></span>d </div>
								<div class="t m0 xb  y270 ff2 fs1 fc1 sc0 ls0 ws0">or terminate Your Account and gogo20
									Services if it believes, in its sole and absolute </div>
								<div class="t m0 xb  y271 ff2 fs1 fc1 sc0 ls0 ws0">discretion that You have infringed,
									breached, violated, abused, or unethically </div>
								<div class="t m0 xb  y272 ff2 fs1 fc1 sc0 ls0 ws0">manipulated or exploited any term of
									these Terms of Use or anyway oth<span class="_ _1"></span>erwise </div>
								<div class="t m0 xb  y273 ff2 fs1 fc1 sc0 ls0 ws0">acted unethically. </div>
								<div class="t m0 xb  y274 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y275 ff2 fs1 fc1 sc0 ls0 ws0">20.3.<span class="ff8"> <span
											class="_ _7"> </span></span>You hereby agree and acknowledge, upon
									termination,
									gogo20 shall have the </div>
								<div class="t m0 xb  y276 ff2 fs1 fc1 sc0 ls0 ws0">right to retain all information
									pertaining to t<span class="_ _1"></span>he transactions initiate<span
										class="_ _0"></span>d by You on the </div>
								<div class="t m0 xb  y277 ff2 fs1 fc1 sc0 ls0 ws0">Platform. </div>
								<div class="t m0 xb  y278 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y279 ff2 fs1 fc1 sc0 ls0 ws0">20.4.<span class="ff8"> <span
											class="_ _7"> </span></span>You hereby further agree and acknowledge that
									nothing contained i<span class="_ _1"></span>n this </div>
								<div class="t m0 xb  y27a ff2 fs1 fc1 sc0 ls0 ws0">Clause 20 shall be construed as a
									waiver
									of gogo20<span class="ff3">’s and/or Delivery Partner’s right </span></div>
								<div class="t m0 xb  y27b ff2 fs1 fc1 sc0 ls0 ws0">to payment of the outstanding dues.
									You
									hereby further agree and ack<span class="_ _1"></span>now<span
										class="_ _0"></span>ledg<span class="ls1">e </span></div>
								<div class="t m0 xb  y27c ff2 fs1 fc1 sc0 ls0 ws0">that on or before termination, you
									shall
									ensure that all the monies due to be paid to </div>
								<div class="t m0 xb  y27d ff2 fs1 fc1 sc0 ls0 ws0">gogo20 and/or Delivery Partner are
									paid
									in a tim<span class="_ _1"></span>ely manner<span class="_ _0"></span>. </div>
								<div class="t m0 xb  y6a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y27e ff2 fs1 fc1 sc0 ls0 ws0">20.5.<span class="ff8"> <span
											class="_ _7"> </span></span>You hereby further agree and acknowledge that in
									case of non-p<span class="_ _0"></span>ayment of </div>
								<div class="t m0 xb  y27f ff2 fs1 fc1 sc0 ls0 ws0">dues within the prescribed timelines:
									(i)
									gogo20 shall not in any manner be liable to </div>
								<div class="t m0 xb  y1b6 ff2 fs1 fc1 sc0 ls0 ws0">Delivery Partner for payment of such
									due;
									and (ii) gogo20 may: (a) adjust the </div>
								<div class="t m0 xb  y280 ff2 fs1 fc1 sc0 ls0 ws0">amount due from the amount payable by
									gogo20 to You; and (b) at its sole </div>
								<div class="t m0 xb  y281 ff2 fs1 fc1 sc0 ls0 ws0">discretion take appropriate legal
									action
									against You to recover the same and/or on </div>
								<div class="t m0 xb  y282 ff2 fs1 fc1 sc0 ls0 ws0">receiving a request, facilitate
									Delivery
									Partner for such recovery. </div>
								<div class="t m0 xb  y283 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h5 y229 ff1 fs1 fc1 sc0 ls3 ws0">21.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Governing Law </span></span></div>
								<div class="t m0 x3  y284 ff2 fs1 fc1 sc0 ls0 ws0">These Terms of Use shall be governed
									by
									and constructed i<span class="_ _1"></span>n accordance with the laws of </div>
								<div class="t m0 x3  y285 ff2 fs1 fc1 sc0 ls0 ws0">Nepal without reference to conflict
									of
									laws principles and disputes arising in relation </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf1f" class="pf w0 h0" data-page-no="1f">
						<div class="pc pc1f w0 h0"><img class="bi x7 y286 w3 h1c" alt="" src="bg1f.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 x3  y22c ff2 fs1 fc1 sc0 ls0 ws0">hereto shall be subject to the
									exclusive
									jurisdiction of courts, tribunals, <span class="_ _1"></span>forum, applicable
								</div>
								<div class="t m0 x3  y22d ff2 fs1 fc1 sc0 ls0 ws0">authorities at Kathmandu, Nepal.
								</div>
								<div class="t m0 x2 h5 y263 ff1 fs1 fc1 sc0 ls3 ws0">22.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Report Abuse </span></span></div>
								<div class="t m0 x3  y287 ff2 fs1 fc1 sc0 ls0 ws0">In the event You come across any
									abuse or
									violation of these Terms of Use or i<span class="_ _1"></span>f You </div>
								<div class="t m0 x3  y288 ff2 fs1 fc1 sc0 ls0 ws0">become aware of any objectionable
									content
									on the Platform, please repo<span class="_ _1"></span>rt the same to </div>
								<div class="t m0 x3  y289 ff2 fs1 fc1 sc0 ls0 ws0">the following e-mail id:
									contact@gogo20.com. In case You have any queries with respect </div>
								<div class="t m0 x3  y28a ff2 fs1 fc1 sc0 ls0 ws0">to the Terms of Use or the gogo20
									Services, please write to Us at support@gogo20.com </div>
								<div class="t m0 x2 h5 y28b ff1 fs1 fc1 sc0 ls3 ws0">23.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">Communication </span></span></div>
								<div class="t m0 x3  y28c ff2 fs1 fc1 sc0 ls0 ws0">You hereby expressly agree to receive
									communica<span class="_ _1"></span>tions by way of SMSs and/or e<span
										class="_ _0"></span>-mails </div>
								<div class="t m0 x3  y28d ff2 fs1 fc1 sc0 ls0 ws0">from gogo20, or other third parties.
									You
									can unsubscribe/ opt-out from receiving </div>
								<div class="t m0 x3  y28e ff2 fs1 fc1 sc0 ls0 ws0">communications through SMS and e-mail
									anytime by contacting us for the same. </div>
								<div class="t m0 x3  y28f ff2 fs1 fc1 sc0 ls0 ws0">However, you may still receive
									communications from Your en<span class="_ _1"></span>d with respect to Your use
								</div>
								<div class="t m0 x3  y290 ff2 fs1 fc1 sc0 ls0 ws0">of the gogo20 Service. </div>
								<div class="t m0 x2 h5 y291 ff1 fs1 fc1 sc0 ls3 ws0">24.<span class="ff5 ls0"> <span
											class="_ _5"></span><span class="ff1">General </span></span></div>
								<div class="t m0 x3  y292 ff1 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y293 ff2 fs1 fc1 sc0 ls0 ws0">24.1.<span class="ff8"> <span
											class="_ _7"> </span></span>Amendments: gogo20 reserves the unconditional
									right
									to modify or amend </div>
								<div class="t m0 xb  ya4 ff2 fs1 fc1 sc0 ls0 ws0">these Terms of Use without any
									requirement
									to notify You of the same. You can </div>
								<div class="t m0 xb  y294 ff2 fs1 fc1 sc0 ls0 ws0">determine when these Terms of Use
									were
									last m<span class="_ _1"></span>odified by referring to the<span
										class="_ _0"></span>
									<span class="ff3">“Last</span> </div>
								<div class="t m0 xb  y295 ff3 fs1 fc1 sc0 ls0 ws0">Updated”<span class="ff2"> legend
										above.
										It shall be Your responsibility to check this Terms of Use </span></div>
								<div class="t m0 xb  y23e ff2 fs1 fc1 sc0 ls0 ws0">periodically for changes. Your
									acceptance
									of the amended Terms of Use shall signify<span class="_ _1"></span> </div>
								<div class="t m0 xb  y296 ff2 fs1 fc1 sc0 ls0 ws0">Your consent to such changes and
									agreement to be legally bound by the sa<span class="_ _1"></span>me.<span
										class="_ _0"></span> </div>
								<div class="t m0 xb  y297 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y298 ff2 fs1 fc1 sc0 ls0 ws0">24.2.<span class="ff8"> <span
											class="_ _7"> </span></span>Notice: All notices from gogo20 will be served
									by
									email to Your registered </div>
								<div class="t m0 xb  y8c ff2 fs1 fc1 sc0 ls0 ws0">email address or by general
									notification
									on the Pl<span class="_ _1"></span>atform.<span class="_ _0"></span> </div>
								<div class="t m0 xb  y299 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y29a ff2 fs1 fc1 sc0 ls0 ws0">24.3.<span class="ff8"> <span
											class="_ _7"> </span></span>Assignment: You cannot assign or otherwise
									transfer
									the Terms of Use, or any </div>
								<div class="t m0 xb  y29b ff2 fs1 fc1 sc0 ls0 ws0">rights granted hereunder to any third
									party. <span class="_ _0"></span>gogo20<span class="ff3">’s rig<span
											class="_ _1"></span>hts under the Terms of Use </span></div>
								<div class="t m0 xb  y29c ff2 fs1 fc1 sc0 ls0 ws0">are freely transferable by gogo20 to
									any
									third party without the requirement of </div>
								<div class="t m0 xb  y29d ff2 fs1 fc1 sc0 ls0 ws0">informing You or seeking Your
									consent.
								</div>
								<div class="t m0 xb  y29e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y29f ff2 fs1 fc1 sc0 ls0 ws0">24.4.<span class="ff8"> <span
											class="_ _7"> </span></span>Force Majeure: Any delay in or failure to
									perform
									any obligations by eithe<span class="_ _1"></span>r </div>
								<div class="t m0 xb  y2a0 ff2 fs1 fc1 sc0 ls0 ws0">party under these Terms of Use shall
									not
									constitute default hereunder i<span class="_ _1"></span>f<span class="_ _0"></span>
									and
									to the </div>
								<div class="t m0 xb  y2a1 ff2 fs1 fc1 sc0 ls0 ws0">extent caused by force majeure, which
									is
									define<span class="_ _1"></span>d to be occurrences beyond the </div>
								<div class="t m0 xb  y2a2 ff2 fs1 fc1 sc0 ls0 ws0">reasonable control of such party
									committing default, including and limited to acts of </div>
								<div class="t m0 xb  y2a3 ff2 fs1 fc1 sc0 ls0 ws0">the government authorities, acts of
									God,
									fire, floo<span class="_ _1"></span>d, explosion, riots, war, lab<span
										class="_ _0"></span>our </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf20" class="pf w0 h0" data-page-no="20">
						<div class="pc pc20 w0 h0">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xb  y27 ff2 fs1 fc1 sc0 ls0 ws0">strikes, sabotage, rebellion,
									insurrection, epidemi<span class="_ _1"></span>c, pandemics or similar outbreak
								</div>
								<div class="t m0 xb  y14d ff3 fs1 fc1 sc0 ls0 ws0">(“Force Majeure”). Provided, however,
									<span class="ff2">y<span class="ls3">ou</span> shall give prompt written notice
										within a
									</span></div>
								<div class="t m0 xb  y14e ff2 fs1 fc1 sc0 ls0 ws0">period of 7 (seven) days from the
									date of
									the force majeure occ<span class="_ _1"></span>urrence to <span
										class="_ _0"></span>gogo20<span class="ls9">. </span></div>
								<div class="t m0 xb  y14f ff2 fs1 fc1 sc0 ls3 ws0">You<span class="ls0"> shall use all
										reasonable efforts to avoid or<span class="_ _1"></span> remove such cause of
										non<span class="_ _0"></span>-</span></div>
								<div class="t m0 xb  y150 ff2 fs1 fc1 sc0 ls0 ws0">performance and shall continue
									performance hereunder whenever such causes of </div>
								<div class="t m0 xb  y151 ff2 fs1 fc1 sc0 ls0 ws0">force majeure are removed. In the
									event
									the Force Majeure event continues for a </div>
								<div class="t m0 xb  y152 ff2 fs1 fc1 sc0 ls0 ws0">period of 7 (seven) days from the
									date on
									which gogo20 receives the notice from </div>
								<div class="t m0 xb  y153 ff2 fs1 fc1 sc0 ls0 ws0">You as above, gogo20 shall have the
									right
									to terminate these Terms of Use<span class="_ _1"></span>. </div>
								<div class="t m0 xb  y154 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y155 ff2 fs1 fc1 sc0 ls0 ws0">24.5.<span class="ff8"> <span
											class="_ _7"> </span></span>No Agency: Merchant shall not be deemed to be
									gogo20<span class="ff3">’s agent, servant, or </span></div>
								<div class="t m0 xb  y156 ff2 fs1 fc1 sc0 ls0 ws0">employee in any manner for any
									purpose
									whatsoever. </div>
								<div class="t m0 xb  y157 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y158 ff2 fs1 fc1 sc0 ls0 ws0">24.6.<span class="ff8"> <span
											class="_ _7"> </span></span>Severability: If, for any reason, a court of
									competent jurisdiction finds any </div>
								<div class="t m0 xb  y159 ff2 fs1 fc1 sc0 ls0 ws0">provision of the Terms of Use, or any
									portion thereof, to be unenforceable, that </div>
								<div class="t m0 xb  y15a ff2 fs1 fc1 sc0 ls0 ws0">provision shall be enforced to the
									maximum extent permissi<span class="_ _1"></span>ble so as to give effect </div>
								<div class="t m0 xb  y15b ff2 fs1 fc1 sc0 ls0 ws0">to the intent of the parties as
									reflected
									by that provision, and the remainder of the </div>
								<div class="t m0 xb  y15c ff2 fs1 fc1 sc0 ls0 ws0">Terms of Use shall continue in full
									force
									and effect<span class="_ _1"></span>. </div>
								<div class="t m0 xb  y15d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y15e ff2 fs1 fc1 sc0 ls0 ws0">24.7.<span class="ff8"> <span
											class="_ _7"> </span></span>Waiver: Any failure by gogo20 to enforce or
									exercise
									any provision of the </div>
								<div class="t m0 xb  y15f ff2 fs1 fc1 sc0 ls0 ws0">Terms of Use, or any related right,
									shall
									not constitute a waiver by gogo20 of that </div>
								<div class="t m0 xb  y160 ff2 fs1 fc1 sc0 ls0 ws0">provision or right. </div>
								<div class="t m0 xb  y161 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y162 ff2 fs1 fc1 sc0 ls0 ws0">24.8.<span class="ff8"> <span
											class="_ _7"> </span></span>Equitable Remedies: Merchant acknowledge and
									agrees
									that monetary </div>
								<div class="t m0 xb  y163 ff2 fs1 fc1 sc0 ls0 ws0">damages may be an inadequate remedy
									for
									breach or t<span class="_ _1"></span>hreatened breach of the </div>
								<div class="t m0 xb  y164 ff2 fs1 fc1 sc0 ls0 ws0">provisions of these Terms of Use, and
									Merchant agrees that in the event of a breach </div>
								<div class="t m0 xb  y165 ff2 fs1 fc1 sc0 ls0 ws0">of any provisions of these Terms of
									Use
									by the Merchant, gogo20<span class="ff3">’s rights and </span></div>
								<div class="t m0 xb  y166 ff2 fs1 fc1 sc0 ls0 ws0">obligations hereunder, in addition to
									any
									and all other rights and remedies that may </div>
								<div class="t m0 xb  y167 ff2 fs1 fc1 sc0 ls0 ws0">be available to gogo20 in respect of
									such
									breach, shall be enforceable by specific </div>
								<div class="t m0 xb  y168 ff2 fs1 fc1 sc0 ls0 ws0">performance, injunctive remedy or any
									other remedy available in a<span class="_ _1"></span>ny court of </div>
								<div class="t m0 xb  y169 ff2 fs1 fc1 sc0 ls0 ws0">competent jurisdiction. </div>
								<div class="t m0 xb  y16a ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y16b ff2 fs1 fc1 sc0 ls0 ws0">24.9.<span class="ff8"> <span
											class="_ _7"> </span></span>Integration: These Terms of Use together with
									gogo20<span class="ff3">’s Privacy Policy and </span></div>
								<div class="t m0 xb  y16c ff2 fs1 fc1 sc0 ls0 ws0">any other legal notices,
									communications
									publishe<span class="_ _1"></span>d by <span class="_ _0"></span>gogo20 on its
									Platform,
									and </div>
								<div class="t m0 xb  y16d ff2 fs1 fc1 sc0 ls0 ws0">any other agreements executed between
									You
									and gogo20 shall constitute the entire </div>
								<div class="t m0 xb  y16e ff2 fs1 fc1 sc0 ls0 ws0">agreement between you and gogo20
									concerning its Platform, gogo20 Services and </div>
								<div class="t m0 xb  y16f ff2 fs1 fc1 sc0 ls0 ws0">governs Your use of the Platform and
									gogo20 <span class="_ _0"></span>Servic<span class="_ _1"></span>e, superseding any
									prior </div>
								<div class="t m0 xb  y170 ff2 fs1 fc1 sc0 ls0 ws0">agreements between You and gogo20
									with
									respect to the Platform and gogo20 </div>
								<div class="t m0 xb  y171 ff2 fs1 fc1 sc0 ls0 ws0">Service. </div>
								<div class="t m0 xb  y172 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x3 hf y173 ff2 fs1 fc1 sc0 ls0 ws0">24.10.<span class="ff8"> <span
											class="_ _9"> </span></span>Infringement: If You believe the Platform
									violates
									Your intellectual property, </div>
								<div class="t m0 xb  y174 ff2 fs1 fc1 sc0 ls0 ws0">You must promptly notify gogo20 in
									writing at legalnotices@gogo20.in These </div>
								<div class="t m0 xb  y12a ff2 fs1 fc1 sc0 ls0 ws0">notifications should only be
									submitted by
									the owner of the intellectual property or </div>
								<div class="t m0 xb  y175 ff2 fs1 fc1 sc0 ls0 ws0">an agent duly authorized to act on
									his/her behalf. However, any false claim<span class="_ _1"></span> by Y<span
										class="_ _0"></span>ou </div>
								<div class="t m0 xb  y176 ff2 fs1 fc1 sc0 ls0 ws0">may result in the termination of Your
									access to the Platform. You are required to </div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					<div id="pf21" class="pf w0 h0" data-page-no="21">
						<div class="pc pc21 w0 h0"><img class="bi x2 y2a4 w7 h1d" alt="" src="bg21.png">
							<div class="c x1 y1 w2 h0">
								<div class="t m0 xb  y27 ff2 fs1 fc1 sc0 ls0 ws0">provide the following details in Your
									noti<span class="ls2">ce</span>: </div>
								<div class="t m0 xb  y14d ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y14e ff2 fs1 fc1 sc0 ls0 ws0">24.10.1.<span class="ff8"> <span
											class="_ _8"> </span></span>The intellectual property that You believe is
									being<span class="_ _1"></span> infringed;<span class="_ _0"></span> </div>
								<div class="t m0 xc  y14f ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y150 ff2 fs1 fc1 sc0 ls0 ws0">24.10.2.<span class="ff8"> <span
											class="_ _8"> </span></span>The item that You think is infringing and
									include
									suff<span class="_ _0"></span>icient informatio<span class="_ _1"></span>n </div>
								<div class="t m0 xc  y151 ff2 fs1 fc1 sc0 ls0 ws0">about where the material is located
									on
									the Platform; </div>
								<div class="t m0 xc  y152 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y153 ff2 fs1 fc1 sc0 ls0 ws0">24.10.3.<span class="ff8"> <span
											class="_ _8"> </span></span>A statement that You believe in good faith that
									the
									item You have </div>
								<div class="t m0 xc  y154 ff2 fs1 fc1 sc0 ls0 ws0">identified as infringing is not
									authorized by the intellectual property owner, its </div>
								<div class="t m0 xc  y155 ff2 fs1 fc1 sc0 ls0 ws0">agent, or the law to be used in
									connection with the Platform; </div>
								<div class="t m0 xc  y156 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y157 ff2 fs1 fc1 sc0 ls0 ws0">24.10.4.<span class="ff8"> <span
											class="_ _8"> </span></span>Your contact details, such as Your address,
									telephone number, and/or </div>
								<div class="t m0 xc  y158 ff2 fs1 fc1 sc0 ls0 ws0">email; </div>
								<div class="t m0 xc  y159 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y15a ff2 fs1 fc1 sc0 ls0 ws0">24.10.5.<span class="ff8"> <span
											class="_ _8"> </span></span>A statement that the information You provided in
									Your notice is </div>
								<div class="t m0 xc  y15b ff2 fs1 fc1 sc0 ls0 ws0">accurate, and that You are the
									intellectual property owner or an agent </div>
								<div class="t m0 xc  y15c ff2 fs1 fc1 sc0 ls7 ws0">au<span class="ls0">thorized to act
										on
										behalf of the intellectual property owner wh<span class="_ _1"></span>ose
									</span>
								</div>
								<div class="t m0 xc  y15d ff2 fs1 fc1 sc0 ls0 ws0">intellectual property is being
									infringed;
									and </div>
								<div class="t m0 xc  y15e ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x4 hf y15f ff2 fs1 fc1 sc0 ls0 ws0">24.10.6.<span class="ff8"> <span
											class="_ _8"> </span></span>Your physical or electronic signature. </div>
								<div class="t m0 xc  y160 ff2 fs1 fc1 sc0 ls0 ws0"> </div>
								<div class="t m0 x2 h1e y2a5 ff9 fs2 fc1 sc0 ls0 ws0">Copyright © <span
										class="lsf">All</span> rights <span class="_ _0"></span>reserved.<span
										class="_ _0"></span><span class="ff2 fs3"> </span></div>
							</div>
						</div>
						<div class="pi"
							data-data="{&quot;ctm&quot;:[1.000000,0.000000,0.000000,1.000000,0.000000,0.000000]}"></div>
					</div>
					@endif
				</div>
			</div>

		</div>
		</div>

	</section>
</main>

@endsection

@push('script')
<script src="{{ frontendAsset('/frontend/node_modules/scroll-out/dist/scroll-out.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/jquery.js') }}"></script>
<script type="text/javascript" src="{{ frontendAsset('/frontend/js/parallax.js') }}"></script>

@endpush