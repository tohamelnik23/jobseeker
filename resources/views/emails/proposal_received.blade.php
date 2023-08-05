
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Actionable emails e.g. reset password</title>
</head>
<style>
/* -------------------------------------
    GLOBAL
    A very basic CSS reset
------------------------------------- */
* {
  margin: 0;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  box-sizing: border-box;
  font-size: 15px;
}

img {
  max-width: 100%;
}

body {
  -webkit-font-smoothing: antialiased;
  -webkit-text-size-adjust: none;
  width: 100% !important;
  height: 100%;
  line-height: 1.6em;
  /* 1.6em * 14px = 22.4px, use px to get airier line-height also in Thunderbird, and Yahoo!, Outlook.com, AOL webmail clients */
  /*line-height: 22px;*/
}

/* Let's make sure all tables have defaults */
table td {
  vertical-align: top;
}

/* -------------------------------------
    BODY & CONTAINER
------------------------------------- */
body {
  background-color: #ecf0f5;
  color: #222;
}

.body-wrap {
  background-color: #ecf0f5;
  width: 100%;
}

.container {
  display: block !important;
  max-width: 600px !important;
  margin: 0 auto !important;
  /* makes it centered */
  clear: both !important;
}

.content {
  max-width: 600px;
  margin: 0 auto;
  display: block;
  padding: 20px;
}

/* -------------------------------------
    HEADER, FOOTER, MAIN
------------------------------------- */
.main {
  background-color: #fff;
  border-bottom: 2px solid #d7d7d7;
}

.content-wrap {
  padding: 20px;
}

.content-block {
  padding: 0 0 20px;
}

.header {
  width: 100%;
  margin-bottom: 20px;
}

.footer {
  width: 100%;
  clear: both;
  color: #999;
  padding: 20px;
}
.footer p, .footer a, .footer td {
  color: #999;
  font-size: 12px;
}
/* -------------------------------------
    TYPOGRAPHY
------------------------------------- */
h1, h2, h3 {
  font-family: "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
  color: #1a2c3f;
  margin: 30px 0 0;
  line-height: 1.2em;
  font-weight: 400;
}
h1 {
  font-size: 32px;
  font-weight: 500;
  /* 1.2em * 32px = 38.4px, use px to get airier line-height also in Thunderbird, and Yahoo!, Outlook.com, AOL webmail clients */
  /*line-height: 38px;*/
}

h2 {
  font-size: 24px;
  /* 1.2em * 24px = 28.8px, use px to get airier line-height also in Thunderbird, and Yahoo!, Outlook.com, AOL webmail clients */
  /*line-height: 29px;*/
}

h3 {
  font-size: 18px;
  /* 1.2em * 18px = 21.6px, use px to get airier line-height also in Thunderbird, and Yahoo!, Outlook.com, AOL webmail clients */
  /*line-height: 22px;*/
}

h4 {
  font-size: 14px;
  font-weight: 600;
}

p, ul, ol {
  margin-bottom: 10px;
  font-weight: normal;
}
p li, ul li, ol li {
  margin-left: 5px;
  list-style-position: inside;
}

/* -------------------------------------
    LINKS & BUTTONS
------------------------------------- */
a {
  color: #348eda;
  text-decoration: underline;
}

.btn-primary {
  text-decoration: none;
  color: #FFF;
  background-color: #42A5F5;
  border: solid #42A5F5;
  border-width: 10px 20px;
  line-height: 2em;
  /* 2em * 14px = 28px, use px to get airier line-height also in Thunderbird, and Yahoo!, Outlook.com, AOL webmail clients */
  /*line-height: 28px;*/
  font-weight: bold;
  text-align: center;
  cursor: pointer;
  display: inline-block;
  text-transform: capitalize;
}

/* -------------------------------------
    OTHER STYLES THAT MIGHT BE USEFUL
------------------------------------- */
.last {
  margin-bottom: 0;
}

.first {
  margin-top: 0;
}

.aligncenter {
  text-align: center;
}

.alignright {
  text-align: right;
}

.alignleft {
  text-align: left;
}

.clear {
  clear: both;
}

/* -------------------------------------
    ALERTS
    Change the class depending on warning email, good email or bad email
------------------------------------- */
.alert {
  font-size: 16px;
  color: #fff;
  font-weight: 500;
  padding: 20px;
  text-align: center;
}
.alert a {
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  font-size: 16px;
}
.alert.alert-warning {
  background-color: #FFA726;
}
.alert.alert-bad {
  background-color: #ef5350;
}
.alert.alert-good {
  background-color: #8BC34A;
}

/* -------------------------------------
    INVOICE
    Styles for the billing table
------------------------------------- */
.invoice {
  margin: 25px auto;
  text-align: left;
  width: 100%;
}
.invoice td {
  padding: 5px 0;
}
.invoice .invoice-items {
  width: 100%;
}
.invoice .invoice-items td {
  border-top: #eee 1px solid;
}
.invoice .invoice-items .total td {
  border-top: 2px solid #6c7b88;
  font-size: 18px;
}

/* -------------------------------------
    RESPONSIVE AND MOBILE FRIENDLY STYLES
------------------------------------- */
@media only screen and (max-width: 640px) {
  body {
    padding: 0 !important;
  }

  h1, h2, h3, h4 {
    font-weight: 800 !important;
    margin: 20px 0 5px !important;
  }

  h1 {
    font-size: 22px !important;
  }

  h2 {
    font-size: 18px !important;
  }

  h3 {
    font-size: 16px !important;
  }

  .container {
    padding: 0 !important;
    width: 100% !important;
  }

  .content {
    padding: 0 !important;
  }

  .content-wrap {
    padding: 10px !important;
  }

  .invoice {
    width: 100% !important;
  }
}
/*# sourceMappingURL=styles.css.map */
</style>
<body itemscope itemtype="http://schema.org/EmailMessage">
    <table class="body-wrap">
        <tr>
            <td></td>
            <td class="container" width="600">
                <div class="content">
                    <table class="main" width="100%" cellpadding="0" cellspacing="0" itemprop="action" itemscope itemtype="http://schema.org/ConfirmAction">
                        <tr>
                            <td class="content-wrap">
                                <meta itemprop="name" content="Confirm Email"/>
                                <table width="100%" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td>
                                            <img src="{!! asset('img/background/email-header.jpg') !!}">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            <h2 style = "text-align:center">  View your new proposal from {!! $freelancer->accounts->name !!}  </h2>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                            Hi, {{ $to_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                        Please review the new proposal for <strong> {{ $job->headline }} </strong>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td style="font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 24px;padding-top: 20px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="word-break: normal;"> 
                                                <tbody>
                                                    <tr> 
                                                        <td width="80" style="vertical-align: top;font-size: 16px;line-height: 24px;">  
                                                            <table class="mso-hidden_mr_css_attr" border="0" cellpadding="0" cellspacing="0" role="presentation" width="60" style="width: 60px;mso-hide: all;"> 
                                                                <tbody>
                                                                    <tr> 
                                                                        <td> 
                                                                            <div style="-webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;overflow: hidden!important;mso-hide: all;"> 
                                                                                <img src="{{ $freelancer->getImage() }}" border="0" style="-webkit-border-radius: 50%;-moz-border-radius: 50%;border-radius: 50%;display: block;-o-object-fit: cover;object-fit: cover;" alt = "{{ $freelancer->accounts->name }}" width="60" height="60">
                                                                            </div> 
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>  
                                                        <td style="vertical-align: top;font-size: 16px;line-height: 24px;">
                                                            <h5 style="margin-top: 0;margin-bottom: 0;font-family: 'Montserrat', Helvetica, Arial, sans-serif;font-weight:
                                                    700;font-size: 16px;line-height: 24px;color: #222;"> 
                                                        <a href="#" target="_blank" style="color: #37A000;text-decoration: none;word-break: break-word;font-weight: bold;" rel=" noopener noreferrer">{{ $freelancer->accounts->name }}</a> 
                                                            </h5> 
                                                            @php
                                                                $role = $proposal->getProfile();
                                                            @endphp
                                                            @if(isset($role))
                                                            <div style="font-family: Helvetica, Arial, sans-serif;margin-bottom: 5px;color: #222222;word-break: normal;"> 
                                                                @if(strlen($role->role_title) > 45)
                                                                    {{ substr($role->role_title, 0, 45) }} ...
                                                                @else
                                                                    {{ substr($role->role_title, 0, 45) }}
                                                                @endif
                                                            </div>
                                                            @endif
                                                            <div style="font-family: Helvetica, Arial, sans-serif;margin-bottom: 20px;color: #656565;font-size: 14px;line-height: 22px;word-break: normal;">
                                                                <span style="color: #7d7d7d;">Bid: </span>
                                                                @if($job->payment_type == "fixed")
                                                                    <span style="color: #222222;"><span style="font-weight: bold;">${!! number_format($proposal->proposal_amount, 2) !!}</span></span> 
                                                                @else
                                                                    <span style="color: #222222;"><span style="font-weight: bold;">${!! number_format($proposal->proposal_amount, 2) !!} /hr</span></span>     
                                                                @endif
                                                            </div>
                                                        </td> 
                                                    </tr> 
                                                
                                                </tbody>
                                            </table> 
                                            <div style="font-family: Helvetica, Arial, sans-serif;margin-bottom: 20px;word-break: normal;">
                                                <table style="text-align: center;" width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="button-holder_mr_css_attr" style="text-align: center;margin: 0 auto;"> 
                                                                    <a target="_blank" style="background-color: #FFFFFF;border: 2px solid #E0E0E0;border-radius: 2px;color: #37A000;white-space: nowrap;font-weight: bold;display: block;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 26px;text-align: center;text-decoration: none;-webkit-text-size-adjust: none;mso-hide: all;" href="{{  route('employer.jobs.mainaction.user', [$job->serial, $freelancer->serial])  }}" rel=" noopener noreferrer">View Profile</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr> 
                                        <td style="font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 24px;padding-top: 20px;"> 
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tbody>
                                                    <tr>
                                                        <td style="font-size: 16px;line-height: 24px;background-color: #F9F9F9;padding: 20px;"> 
                                                            <h5 style="margin-top: 0;margin-bottom: 0;font-family: 'Montserrat', Helvetica, Arial, sans-serif;font-weight:700; font-size: 16px; line-height: 24px; color: #222;">Cover letter</h5>  
                                                                @if(strlen($proposal->coverletter) > 146)
                                                                    {{ substr($proposal->coverletter, 0, 146) }} ...
                                                                @else
                                                                    {{ substr($proposal->coverletter, 0, 146) }}
                                                                @endif
                                                            @if(strlen($proposal->coverletter) > 146)
                                                            <a href="{{  route('employer.jobs.mainaction.user', [$job->serial, $freelancer->serial])  }}" target="_blank" style="color: #37A000;text-decoration: none;word-break: break-word;font-weight: bold;" rel=" noopener noreferrer">more</a>
                                                            @endif
                                                        </td> 
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block aligncenter" itemprop="handler" itemscope="" itemtype="http://schema.org/HttpActionHandler">
                                            <a href="{{ route('employer.jobs.mainaction.user', [$job->serial, $freelancer->serial])  }}" class="btn-primary" itemprop="url">View Full Proposal</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="content-block">
                                        Thanks, DiscoverGigs.com Team
                                        </td>
                                    </tr>
                                    @php
                                        $setting_link = "email_proposal_received-checkbox";
                                    @endphp
                                    @include('emails.turn_off')
                                </table>
                            </td>
                        </tr>
                    </table>
                    <div class="footer">
                        <table width="100%">
                            <tr>
                                <td class="aligncenter content-block">Follow <a href="#">@MyCompany</a> on Twitter.</td>
                            </tr>
                        </table>
                    </div></div>
            </td>
            <td></td>
        </tr>
    </table>
</body>
</html>