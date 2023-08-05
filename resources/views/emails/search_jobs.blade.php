
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
	  font-size: 14px;
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
	  color: #6c7b88
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
												<h2 style = "text-align:center">
                                                    @if( count($jobs) == 1 )
                                                        {!! $to_name !!}, here are new gig you may be interested in.
                                                    @else
                                                        {!! $to_name !!}, here are {!! count($jobs) !!} new jos you may be interested in.
                                                    @endif
                                                </h2>
											</td>
										</tr>
										<tr>
											<td style="color:#222222;font-size:16px;font-family:Helvetica,Arial,Sans-serif;padding:0 10px;line-height:24px;" align="left">
                                                @foreach($jobs as $job)
												<table width="100%" cellspacing="0" cellpadding="0" border="0"> 
													<tbody>
														<tr>
															<td>
																<table cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td style="padding-bottom:5px;">
																				<a href="{!! route('jobs_details', $job->serial) !!}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#37A000;font-size:16px;font-family:Montserrat,Helvetica,Arial,Sans-serif;font-weight:bold;text-decoration:none;line-height:24px;" data-linkindex="4">{!! $job->headline !!}&nbsp;&nbsp; </a> 
																			</td>
																		</tr>
																		<tr>
																			<td style="color:#222222;font-size:14px;font-family:Helvetica,Arial,Sans-serif;padding-bottom:5px;line-height:22px;">
                                                                                @if($job->payment_type == "fixed")
                                                                                    <b>Fixed</b>
                                                                                @endif 
                                                                                @if($job->payment_type == "hourly")
                                                                                    <b>Hourly</b>
                                                                                @endif 
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
															<td style="padding-top:5px;padding-bottom:10px;padding-left:20px;" valign="top" align="right">
																<table style="width:110px;border:2px solid #E0E0E0;" cellspacing="0" cellpadding="0" border="0" align="right">
																	<tbody>
																		<tr>
																			<td style="font-family:Helvetica,Arial,Sans-serif;border-radius:2px;" bgcolor="white" align="center">
                                                                                <a href="{!! route('jobs_details', $job->serial) !!}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#37A000;font-size:14px;font-family:Helvetica,Arial,Sans-serif;font-weight:bold;text-align:center;background-color:white;display:block;text-decoration:none;white-space:nowrap;border-radius:2px;padding:6px 14px;border-width:0;line-height:18px;" data-linkindex="5">
                                                                                    View Gig
                                                                                </a> 
																			</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr> 
														<tr>
															<td colspan="2" style="color:#222222;font-size:16px;font-family:Helvetica,Arial,Sans-serif;line-height:24px;">
																<a href="{!! route('jobs_details', $job->serial) !!}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#222222;text-decoration:none;" data-linkindex="6">
                                                                @if(strlen($job->description) > 130 )
                                                                    {{ substr($job->description, 0, 130) }} ...
                                                                @else
                                                                    {{ $job->description }}
                                                                @endif 
																</a>
																@if(strlen($job->description) > 130 )
																<a href="{!! route('jobs_details', $job->serial) !!}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#37A000;font-weight:bold;text-decoration:none;" data-linkindex="7">
                                                                    more
                                                                </a>
																@endif																
															</td>
														</tr>
														<tr>
															<td colspan="2" style="padding:10px 0 0 0;line-height:30px;">
                                                            @php
                                                                $skills = $job->getSkills();
                                                            @endphp
                                                            @if(count($skills))
                                                                @foreach($skills as $skill_index => $skill)
                                                                    <table cellspacing="0" cellpadding="0" border="0" align="left">
                                                                        <tbody>
                                                                            <tr>
                                                                                <td colspan="2" style="font-size:10px;line-height:10px;" height="10">&nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td style="font-size:14px;font-family:Helvetica,Arial,Sans-serif;font-weight:normal;vertical-align:middle;background-color:#F2F2F2;border-radius:3px;padding:3px 5px;line-height:15px;">
                                                                                    <a href = "{!! route('jobs_details', $job->serial) !!}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#222222;font-family:Helvetica,Arial,Sans-serif;text-decoration:none;" data-linkindex="9">
                                                                                        {!! $skill->skill !!}
                                                                                    </a>
                                                                                </td>
                                                                                <td style="font-size:5px;width:5px;">&nbsp;</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                @endforeach
															@endif
															</td>
														</tr>  
														<tr>
															<td colspan="2">
																<table width="100%" cellspacing="0" cellpadding="0" border="0">
																	<tbody>
																		<tr>
																			<td style="font-size:20px;line-height:20px;" height="20">&nbsp;</td>
																		</tr>
																		<tr>
																			<td style="font-size:17px;border-top:1px solid #E0E0E0;line-height:17px;" height="17">
																			&nbsp;</td>
																		</tr>
																	</tbody>
																</table>
															</td>
														</tr> 
													</tbody>
												</table>
                                                @endforeach
											</td>
										</tr> 	
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td style="color:#222222;font-size:18px;font-family:Montserrat,Helvetica,Arial,Sans-serif;font-weight:bold;padding:0 10px 19px 10px;line-height:26px;" align="left">
													About this email
												</td>
											</tr>
											<tr>
												<td style="color:#222222;font-size:16px;font-family:Helvetica,Arial,Sans-serif;padding:0 10px 27px 10px;border-bottom:3px solid #F2F2F2;line-height:24px;" align="left">
													These recommendations are based on your profile, skills and history. You can change how often you receive these in the Gig Recommendations section on your <a href="{!! route('settings.notifications') !!}#email-discovergigs-checkbox" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:#37A000;font-size:16px;font-family:Helvetica,Arial,Sans-serif;font-weight:bold;text-decoration:none;line-height:24px;" data-linkindex="78">notification settings</a>. 
												</td>
											</tr>
										</tbody>
									</table>
									<table width="100%" cellspacing="0" cellpadding="0" border="0">
										<tbody>
											<tr>
												<td style="color:#222222;font-size:18px;font-family:Montserrat,Helvetica,Arial,Sans-serif;font-weight:bold;padding:27px 30px;line-height:26px;" align="center"> Want to view more great gigs?</td>
											</tr>
											<tr>
												<td style="padding-bottom:30px;" align="center">
													<table style="width:280px;" cellspacing="0" cellpadding="0" border="0">
														<tbody>
															<tr>
																<td style="font-family:Helvetica,Arial,Sans-serif;border-radius:2px;" bgcolor="#37A000" align="center">
																	<a href="{!! route('search') !!}" target="_blank" rel="noopener noreferrer" data-auth="NotApplicable" style="color:white;font-size:14px;font-family:Helvetica,Arial,Sans-serif;font-weight:bold;text-align:center;background-color:#37A000;display:block;text-decoration:none;white-space:nowrap;border-radius:2px;padding:11px 30px;border-width:0;line-height:18px;" data-linkindex="79">Visit Gig Search</a> 
																</td>
															</tr>
														</tbody>
													</table>
												</td>
											</tr>
										</tbody>
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
						</div>
					</div>
				</td>
				<td></td>
			</tr>
		</table>
	</body>
</html> 