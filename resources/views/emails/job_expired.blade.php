
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
  color: #222
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
@media only screen and (max-width: 600px) {
	.col-md-6_mr_css_attr {
		width: 50%;
		float: left;
	}
} 

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
												<h3 style = "text-align:center; font-weight: bold;"> 
													Don't Give Up Now!
												</h3>
											</td>
										</tr>
										<tr>
											<td class="content-block">
												Hi, {{ $to_name }}
											</td>
										</tr> 
										<tr>
											<td class="content-block"> 
												Your post for <a class = "text-mint" href = "{{  route('employer.jobs.mainaction', [$job->serial, 'job-details'])   }}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#42A5F5;font-weight:bold;text-decoration:none;word-break:break-word;" data-linkindex="3"> {{ $job->headline }} </a> has expired.  You can post a new {{ $job->type }} or re-post your previously expired using the buttons below!  We recommend stirring things up to attract more talent.  Look at adjusting scope, adding compensation, or including more details to get the right person to get your gig done!
											</td>
										</tr> 
										<tr>
											<td class="card-row_mr_css_attr" style="font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 24px;word-break: break-word;padding-left: 20px;padding-right: 20px;padding-top: 10px;margin-left: px;margin-right: px;">
												<div style="font-family: Helvetica, Arial, sans-serif;word-break: normal;" class="row_mr_css_attr">
													<div style="font-family: Helvetica, Arial, sans-serif;word-break: normal;" class="col-md-6_mr_css_attr">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" style="word-break: normal;">
															<tbody>
																<tr>
																	<td style="padding-top: 20px;vertical-align: top;font-size: 16px;line-height: 24px;">
																		<div style="font-family: Helvetica, Arial, sans-serif;word-break: normal;" class="p-30-left-md_mr_css_attr p-10-right-md_mr_css_attr">
																			<table style="text-align: center;" width="100%" border="0" cellspacing="0" cellpadding="0">
																				<tbody>
																					<tr>
																						<td>
																							<div class="button-holder_mr_css_attr" style="text-align: center;margin: 0 auto;"> 
                                                                                                @if($job->type == "shift")
                                                                                                    <a target="_blank" style="background-color: #42A5F5;border: 2px solid #42A5F5;border-radius: 2px;color: #FFFFFF;white-space: nowrap;font-weight: bold;display: block;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 36px;text-align: center;text-decoration: none;-webkit-text-size-adjust: none;mso-hide: all; text-transform: uppercase;" href = "{{ route('employer.shift.add') }}" rel=" noopener noreferrer">
                                                                                                        Post New Shift
                                                                                                    </a>
                                                                                                @else
                                                                                                    <a target="_blank" style="background-color: #42A5F5;border: 2px solid #42A5F5;border-radius: 2px;color: #FFFFFF;white-space: nowrap;font-weight: bold;display: block;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 36px;text-align: center;text-decoration: none;-webkit-text-size-adjust: none;mso-hide: all; text-transform: uppercase;" href = "{{ route('employer.job.add') }}" rel=" noopener noreferrer">
                                                                                                        Post New Gig
                                                                                                    </a>
                                                                                                @endif
																							</div>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																	</td> 
																</tr> 
															</tbody> 
														</table>
													</div> 
												
													<div style="font-family: Helvetica, Arial, sans-serif;word-break: normal;" class="col-md-6_mr_css_attr">
														<table border="0" cellpadding="0" cellspacing="0" width="100%" style="word-break: normal;">
															<tbody>
																<tr>
																	<td style="padding-top: 20px;vertical-align: top;font-size: 16px;line-height: 24px;">
																		<div style="font-family: Helvetica, Arial, sans-serif;word-break: normal;" class="p-10-left-md_mr_css_attr p-30-right-md_mr_css_attr">
																			<table style="text-align: center;" width="100%" border="0" cellspacing="0" cellpadding="0">
																				<tbody>
																					<tr>
																						<td>
																							<div class="button-holder_mr_css_attr" style="text-align: center;margin: 0 auto;">
																								<a target="_blank" style="background-color: #FFFFFF;border: 2px solid #E0E0E0;border-radius: 2px;color: #42A5F5;white-space: nowrap;font-weight: bold;display: block;font-family: Helvetica, Arial, sans-serif;font-size: 16px;line-height: 36px;text-align: center;text-decoration: none;-webkit-text-size-adjust: none;mso-hide: all;" href="{{ route('employer.jobs.repost', $job->serial) }}" rel=" noopener noreferrer">
																								Repost Existing {{ $job->type }}
																								</a>
																							</div>
																						</td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																	</td>
																</tr>
															</tbody>
														</table>
													</div>
												</div>
											</td>
										</tr>
 
										<tr>
											<td style="font-size:16px;font-family:Helvetica,Arial,sans-serif;padding-top:20px;padding-right:20px;padding-left:20px;word-break:break-word;line-height:24px;">
												<table width="100%" cellspacing="0" cellpadding="0" border="0">
													<tbody>
														<tr>
															<td style="border-top:1px solid #E0E0E0;line-height:0;">&nbsp;</td>
														</tr>
													</tbody>
											</table>
											</td>
										</tr> 
										 
										<tr>
											<td class="content-block" style = "padding-top: 20px;">
											   We appreciate your participation in the DiscoverGigs.com. 
											</td>
										</tr>	 
										<tr>
											<td class="content-block">
											   Thanks, DiscoverGigs.com Team
											</td>
										</tr> 
                                        @php
                                            $setting_link = "email_job_expired-checkbox";
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