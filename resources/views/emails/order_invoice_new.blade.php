<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="x-apple-disable-message-reformatting" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="color-scheme" content="light dark" />
    <meta name="supported-color-schemes" content="light dark" />
    <title></title>
    <style type="text/css" rel="stylesheet" media="all">
    /* Base ------------------------------ */
    
    @import url("https://fonts.googleapis.com/css?family=Nunito+Sans:400,700&display=swap");
    body {
      width: 100% !important;
      height: 100%;
      margin: 0;
      -webkit-text-size-adjust: none;
    }
    
    a {
      color: #3869D4;
    }
    
    a img {
      border: none;
    }
    
    td {
      word-break: break-word;
    }
    
    .preheader {
      display: none !important;
      visibility: hidden;
      mso-hide: all;
      font-size: 1px;
      line-height: 1px;
      max-height: 0;
      max-width: 0;
      opacity: 0;
      overflow: hidden;
    }
    /* Type ------------------------------ */
    
    body,
    td,
    th {
      font-family: "Nunito Sans", Helvetica, Arial, sans-serif;
    }
    
    h1 {
      margin-top: 0;
      color: #333333;
      font-size: 22px;
      font-weight: bold;
      text-align: left;
    }
    
    h2 {
      margin-top: 0;
      color: #333333;
      font-size: 16px;
      font-weight: bold;
      text-align: left;
    }
    
    h3 {
      margin-top: 0;
      color: #333333;
      font-size: 14px;
      font-weight: bold;
      text-align: left;
    }
    
    td,
    th {
      font-size: 16px;
    }
    
    p,
    ul,
    ol,
    blockquote {
      margin: .4em 0 1.1875em;
      font-size: 16px;
      line-height: 1.625;
    }
    
    p.sub {
      font-size: 13px;
    }
    /* Utilities ------------------------------ */
    
    .align-right {
      text-align: right;
    }
    
    .align-left {
      text-align: left;
    }
    
    .align-center {
      text-align: center;
    }
    /* Buttons ------------------------------ */
    
    .button {
      background-color: #3869D4;
      border-top: 10px solid #3869D4;
      border-right: 18px solid #3869D4;
      border-bottom: 10px solid #3869D4;
      border-left: 18px solid #3869D4;
      display: inline-block;
      color: #FFF;
      text-decoration: none;
      border-radius: 3px;
      box-shadow: 0 2px 3px rgba(0, 0, 0, 0.16);
      -webkit-text-size-adjust: none;
      box-sizing: border-box;
    }
    
    .button--green {
      background-color: #22BC66;
      border-top: 10px solid #22BC66;
      border-right: 18px solid #22BC66;
      border-bottom: 10px solid #22BC66;
      border-left: 18px solid #22BC66;
    }
    
    .button--red {
      background-color: #FF6136;
      border-top: 10px solid #FF6136;
      border-right: 18px solid #FF6136;
      border-bottom: 10px solid #FF6136;
      border-left: 18px solid #FF6136;
    }
    
    @media only screen and (max-width: 500px) {
      .button {
        width: 100% !important;
        text-align: center !important;
      }
    }
    /* Attribute list ------------------------------ */
    
    .attributes {
      margin: 0 0 21px;
    }
    
    .attributes_content {
      background-color: #F4F4F7;
      padding: 16px;
    }
    
    .attributes_item {
      padding: 0;
    }
    /* Related Items ------------------------------ */
    
    .related {
      width: 100%;
      margin: 0;
      padding: 25px 0 0 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    
    .related_item {
      padding: 10px 0;
      color: #CBCCCF;
      font-size: 15px;
      line-height: 18px;
    }
    
    .related_item-title {
      display: block;
      margin: .5em 0 0;
    }
    
    .related_item-thumb {
      display: block;
      padding-bottom: 10px;
    }
    
    .related_heading {
      border-top: 1px solid #CBCCCF;
      text-align: center;
      padding: 25px 0 10px;
    }
    /* Discount Code ------------------------------ */
    
    .discount {
      width: 100%;
      margin: 0;
      padding: 24px;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #F4F4F7;
      border: 2px dashed #CBCCCF;
    }
    
    .discount_heading {
      text-align: center;
    }
    
    .discount_body {
      text-align: center;
      font-size: 15px;
    }
    /* Social Icons ------------------------------ */
    
    .social {
      width: auto;
    }
    
    .social td {
      padding: 0;
      width: auto;
    }
    
    .social_icon {
      height: 20px;
      margin: 0 8px 10px 8px;
      padding: 0;
    }
    /* Data table ------------------------------ */
    
    .purchase {
      width: 100%;
      margin: 0;
      padding: 35px 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    
    .purchase_content {
      width: 100%;
      margin: 0;
      padding: 25px 0 0 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    
    .purchase_item {
      padding: 10px 0;
      color: #51545E;
      font-size: 15px;
      line-height: 18px;
    }
    
    .purchase_heading {
      padding-bottom: 8px;
      border-bottom: 1px solid #EAEAEC;
    }
    
    .purchase_heading p {
      margin: 0;
      color: #85878E;
      font-size: 12px;
    }
    
    .purchase_footer {
      padding-top: 15px;
      border-top: 1px solid #EAEAEC;
    }
    
    .purchase_total {
      margin: 0;
      text-align: right;
      color: #333333;
    }
    
    .purchase_total--label {
      padding: 0 15px 0 0;
    }
    
    body {
      background-color: #F2F4F6;
      color: #51545E;
    }
    
    p {
      color: #51545E;
    }
    
    .email-wrapper {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #F2F4F6;
    }
    
    .email-content {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    /* Masthead ----------------------- */
    
    .email-masthead {
      padding: 25px 0;
      text-align: center;
    }
    
    .email-masthead_logo {
      width: 94px;
    }
    
    .email-masthead_name {
      font-size: 16px;
      font-weight: bold;
      color: #A8AAAF;
      text-decoration: none;
      text-shadow: 0 1px 0 white;
    }
    /* Body ------------------------------ */
    
    .email-body {
      width: 100%;
      margin: 0;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
    }
    
    .email-body_inner {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      background-color: #FFFFFF;
    }
    
    .email-footer {
      width: 570px;
      margin: 0 auto;
      padding: 0;
      -premailer-width: 570px;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
    }
    
    .email-footer p {
      color: #A8AAAF;
    }
    
    .body-action {
      width: 100%;
      margin: 30px auto;
      padding: 0;
      -premailer-width: 100%;
      -premailer-cellpadding: 0;
      -premailer-cellspacing: 0;
      text-align: center;
    }
    
    .body-sub {
      margin-top: 25px;
      padding-top: 25px;
      border-top: 1px solid #EAEAEC;
    }
    
    .content-cell {
      padding: 45px;
    }
    /*Media Queries ------------------------------ */
    
    @media only screen and (max-width: 600px) {
      .email-body_inner,
      .email-footer {
        width: 100% !important;
      }
    }
    
    @media (prefers-color-scheme: dark) {
      body,
      .email-body,
      .email-body_inner,
      .email-content,
      .email-wrapper,
      .email-masthead,
      .email-footer {
        background-color: #333333 !important;
        color: #FFF !important;
      }
      p,
      ul,
      ol,
      blockquote,
      h1,
      h2,
      h3,
      span,
      .purchase_item {
        color: #FFF !important;
      }
      .attributes_content,
      .discount {
        background-color: #222 !important;
      }
      .email-masthead_name {
        text-shadow: none !important;
      }
    }
    
    :root {
      color-scheme: light dark;
      supported-color-schemes: light dark;
    }
    </style>
    <!--[if mso]>
    <style type="text/css">
      .f-fallback  {
        font-family: Arial, sans-serif;
      }
    </style>
  <![endif]-->
  </head>
  <body>
    <span class="preheader">This is an invoice for your Order. Thank you for choosing gogoMart</span>
    <table class="email-wrapper" width="100%" cellpadding="0" cellspacing="0" role="presentation">
      <tr>
        <td align="center">
          <table class="email-content" width="100%" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
              <td class="email-masthead">
                <a href="https://gogo20.com" class="f-fallback email-masthead_name">
                    <img src="https://gogo20.com/frontend/gallery/gogo20.png" alt="gogo20" width="150px">
              </a>
              </td>
            </tr>
            <!-- Email Body -->
            <tr>
              <td class="email-body" width="570" cellpadding="0" cellspacing="0">
                <table class="email-body_inner" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                  <!-- Body content -->
                  <tr>
                    <td class="content-cell">
                      <div class="f-fallback">
                        <h1>Hi {{ $order->user->first_name }} {{ $order->user->last_name }},</h1>
                        <p>Thanks for using gogoMart service. This is an invoice for your recent order.</p>
                        <table class="attributes" width="100%" cellpadding="0" cellspacing="0" role="presentation">
                          <tr>
                            <td class="attributes_content">
                              <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                <tr>
                                  <td class="attributes_item">
                                    <span class="f-fallback">
                                        <strong>Amount:</strong> {{ $order->total }}
                                    </span>
                                  </td>
                                </tr>
                                <tr>
                                  <td class="attributes_item">
                                    <span class="f-fallback">
                                        <strong>Payment Mode:</strong> {{ $order->payment_mode }}
                                    </span>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>
                        <table class="purchase" width="100%" cellpadding="0" cellspacing="0">
                          <tr>
                            <td>
                              <h3>Order Ref:&nbsp;{{ $order->ref_number }}</h3>
                            </td>
                            <td>
                              <h3 class="align-right">Order at: {{ $order->created_at }}</h3>
                            </td>
                          </tr>
                          <tr>
                            <td colspan="2">
                              <table class="purchase_content" width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                  <th class="purchase_heading">
                                    <p class="f-fallback">Description</p>
                                  </th>
                                  <th class="purchase_heading">
                                      <p class="f-fallback">Price</p>
                                  </th>
                                  <th class="purchase_heading">
                                      <p class="f-fallback">Quantity</p>
                                  </th>
                                  <th class="purchase_heading">
                                    <p class="f-fallback">Amount</p>
                                  </th>
                                </tr>
                                <?php
                                  $discount = 0;
                                  $eliteSaving = 0;
                                  $serviceCharge = 0;
                                  $vat = 0;
                                  foreach($order->orderItems as $orderItem){
                                    $discount += ($orderItem->discount_type == 'amount') ? $orderItem->discount : $orderItem->price * ($orderItem->discount/100);
                                    $eliteSaving += ($order->user->elite == 1) ? $orderItem->elite_price : 0;
                                    $serviceCharge += $orderItem->service_charge_amt;
                                    $vat += $orderItem->tax_amt;
                                    ?>
                                    <tr>
                                        <td width="40%" class="purchase_item"><span class="f-fallback">{{ $orderItem->product->title }}</span></td>
                                        <td class="align-center" width="20%" class="purchase_item"><span class="f-fallback">{{ $orderItem->price }}</span></td>
                                        <td class="align-center" width="20%" class="purchase_item"><span class="f-fallback">{{ $orderItem->quantity }}</span></td>
                                        <td class="align-center" width="20%" class="purchase_item"><span class="f-fallback">{{ $orderItem->price*$orderItem->quantity }}</span></td>
                                    </tr>
                                    <?php
                                  }
                                ?>
                                <tr>
                                  <td colspan="3" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total purchase_total--label">Discount</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">{{ $discount }}</p>
                                  </td>
                                </tr>  
                                <tr>
                                  <td colspan="3" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total purchase_total--label">Elite Saving</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">{{ $eliteSaving }}</p>
                                  </td>
                                </tr>
                                <tr>
                                  <td colspan="3" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total purchase_total--label">Service Charge</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">{{ $serviceCharge }}</p>
                                  </td>
                                </tr> 
                                <tr>
                                  <td colspan="3" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total purchase_total--label">VAT</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">{{ $vat }}</p>
                                  </td>
                                </tr> 
                                <tr>
                                  <td colspan="3" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total purchase_total--label">Delivery Charge</p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total">{{ $order->shipping_fee }}</p>
                                  </td>
                                </tr>                      
                                <tr>
                                  <td colspan="3" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total purchase_total--label"> <b>Total</b></p>
                                  </td>
                                  <td width="20%" class="purchase_footer" valign="middle">
                                    <p class="f-fallback purchase_total"> <b>{{ $order->total }}</b></p>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                        </table>                       
                        <p>Thank you,
                          <br>The gogo20 Team</p>
                        <!-- Sub copy -->
                        <table class="body-sub" role="presentation">
                          <tr>
                            <td>
                              <p class="f-fallback sub">If you have any questions about this mail, reach out to our support team for help.</p>
                              <p class="f-fallback sub">WhatsApp: +977 9801212020 <br>
                                Viber: +977 9801212020 <br>
                                Messenger: <a href="https://m.me/gogo20official" target="_blank">gogo20Official</a>  <br>
                                Support email: <a href="mailto:support@gogo20.com">Support</a>
                              </p>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
            <tr>
              <td>
                <table class="email-footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
                    <tr>
                        <td class="content-cell" align="center">
                            <p class="f-fallback sub align-center">&copy;
                                <?= date('Y') ?> gogo20.com. All rights reserved.</p>
                            <p class="f-fallback sub align-center">
                                GOGO20
                                <br>Goreto Tower, Dhumbarahi, Chandol
                                <br> Kathmandu, Nepal.
                            </p>
                        </td>
                    </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
  </body>
</html>