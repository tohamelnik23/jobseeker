<?php
	Route::get('/', 				'HomeController@mainHomePage');
	Route::get('testcall', 			'HomeController@testcall')->name('generate.password');
	Route::get('/login', function () {  return view('auth.login'); }); 
	Route::post('/login',   			'Auth\LoginController@login')->name('login'); 
	Route::group(['prefix' => 'employees'], function () {
		Route::post('create', 			'Auth\EmployeesauthController@create')->name('employees.create'); 
		Route::get('register', 			'Auth\EmployeesauthController@register')->name('employees.register');
	});
	Route::group(['prefix' => 'employers'], function () {
		Route::post('create', 			'Auth\EmployersauthController@create')->name('employers.create'); 
		Route::get('register', 			'Auth\EmployersauthController@register')->name('employers.register');
	});		
	Route::get('/autologout',   		'Auth\LoginController@autologout')->name('autologout');
	Route::post('generate/password', 							'Auth\ForgotPasswordController@generatepassword')->name('generate.password');
	Route::get('password/reset', 								'Auth\ForgotPasswordController@getform')->name('forget.form');
	Route::post('password/reset', 								'Auth\ForgotPasswordController@save')->name('forget.save');
	Route::get('password/reset/phone/{code}', 					'Auth\ForgotPasswordController@phone')->name('forget.form.phone');
	Route::post('password/reset/phone/{code}', 					'Auth\ForgotPasswordController@phone'); 
	Route::get('reset/password/{code}/{uid}', 					'Auth\ResetPasswordController@getform')->name('reset.link.page');
	Route::post('reset/password', 								'Auth\ResetPasswordController@save')->name('reset.save');
	Route::get('skills', 'HomeController@skills')->name('skills');
	Route::get('skillsemployer', 								'HomeController@skillsemployer')->name('skillsemployer'); 
	Route::group(['middleware' => ['auth']], function() {
		Route::get('home', 										'HomeController@index')->name('home');
		Route::get('logout',									'HomeController@logout')->name('logout');
		Route::post('logout',									'HomeController@logout'); 
		Route::get('notification/delete/{id}',  				'HomeController@deletenotification')->name('notification.delete'); 
		Route::get('settings/notification-settings',  			'HomeController@notification_settings')->name('settings.notifications');
		Route::post('settings/notification-settings',			'HomeController@notification_settings');
		Route::get('notifications',  							'HomeController@notifications')->name('notifications');
		Route::get('messages',  								'MessageController@messages')->name('messages');
		Route::post('get_message_content',  					'MessageController@get_message_content')->name('get_message_content');
		Route::post('getuserlist',  							'MessageController@getuserlist')->name('getuserlist');
		Route::post('request_phone_verification', 				'HomeController@request_phone_verification')->name('request_phone_verification');
		Route::post('verify_phone_verification', 				'HomeController@verify_phone_verification')->name('verify_phone_verification');
	});
	Route::get('search/posts', 		 							'SearchController@search')->name('search');
	Route::get('search/employees', 		 						'SearchController@search_employee')->name('search.employee');
	Route::get('search/shifts', 		 						'SearchController@search_shifts')->name('search.shifts');

	Route::get('job/{id}', 	 									'SearchController@jobs_details')->name('jobs_details');
	Route::post('job/getfeedback/{id}', 	 					'SearchController@getfeedback')->name('jobs_details.getfeedback');
	Route::get('freelancers/{id}', 	 							'SearchController@freelancer_detail')->name('freelancers.detail');
	Route::group(['middleware' => ['auth','employee']], function() {
		Route::get('profile/start', 							'Employee\Profile\EmployeesprofileController@start')->name('employee.profile.start');
		Route::post('profile/start', 							'Employee\Profile\EmployeesprofileController@start');  
		Route::post('profile/update/allowcreditcheck',  		'Employee\Profile\EmployeesprofileController@allowcreditcheck')->name('employee.update.allowcreditcheck'); 
		Route::post('profile/update/socialsecuritynumber',  	'Employee\Profile\EmployeesprofileController@socialsecuritynumber')->name('employee.update.socialsecuritynumber'); 
		Route::post('profile/address', 							'Employee\Profile\EmployeesprofileController@address')->name('employee.update.address');  
		Route::post('profile/driverlicense', 					'Employee\Profile\EmployeesprofileController@driverlicense')->name('employee.update.driverlicense');
		Route::post('profile/add/role', 						'Employee\Profile\EmployeesprofileController@addrole')->name('employee.add.role');
		Route::post('profile/update/traveldistance', 			'Employee\Profile\EmployeesprofileController@traveldistance')->name('employee.update.traveldistance'); 
	});  
	Route::group(['middleware' => ['auth','employee', 'employeestart']], function() {
		Route::get('profile', 									'Employee\Profile\EmployeesprofileController@index')->name('employee.profile'); 
		Route::post('profile/add/education', 					'Employee\Profile\EmployeesprofileController@addeducation')->name('employee.add.education');
		Route::post('profile/update/education', 				'Employee\Profile\EmployeesprofileController@updateeducation')->name('employee.update.education');
		Route::post('profile/get/education', 					'Employee\Profile\EmployeesprofileController@geteducation')->name('employee.get.education'); 
		Route::get('profile/delete/education/{id}', 			'Employee\Profile\EmployeesprofileController@deleteeducation')->name('employee.delete.education'); 
		Route::post('profile/update/misc', 						'Employee\Profile\EmployeesprofileController@misc')->name('employee.update.misc');
		Route::post('profile/add/jobhistory', 					'Employee\Profile\EmployeesprofileController@addjobhistory')->name('employee.add.jobhistory');
		Route::post('profile/update/jobhistory', 				'Employee\Profile\EmployeesprofileController@updatejobhistory')->name('employee.update.jobhistory');
		Route::post('profile/get/jobhistory', 					'Employee\Profile\EmployeesprofileController@getjobhistory')->name('employee.get.jobhistory'); 
		Route::get('profile/delete/jobhistory/{id}', 			'Employee\Profile\EmployeesprofileController@deletejobhistory')->name('employee.delete.jobhistory');	
		Route::post('profile/add/certification', 				'Employee\Profile\EmployeesprofileController@addcertification')->name('employee.add.certification');
		Route::post('profile/get/certification', 				'Employee\Profile\EmployeesprofileController@getcertification')->name('employee.get.certification'); 
		Route::post('profile/update/certification', 			'Employee\Profile\EmployeesprofileController@updatecertification')->name('employee.update.certification');
		Route::get('profile/delete/certification/{id}', 		'Employee\Profile\EmployeesprofileController@deletecertification')->name('employee.delete.certification');	
		Route::post('profile/get/role', 						'Employee\Profile\EmployeesprofileController@getrole')->name('employee.get.role');  
		Route::get('profile/delete/role/{id}', 					'Employee\Profile\EmployeesprofileController@deleterole')->name('employee.delete.role'); 
		Route::post('profile/get/availability', 				'Employee\Profile\EmployeesprofileController@getavailability')->name('employee.get.availability');
		Route::post('profile/update/availability', 				'Employee\Profile\EmployeesprofileController@updateavailability')->name('employee.update.availability');
		Route::post('profile/get/traveldistance', 				'Employee\Profile\EmployeesprofileController@gettraveldistance')->name('employee.get.traveldistance'); 
		
		Route::post('media/store', 								'Employee\Media\MediaController@store')->name('media.store');
		Route::post('media/delete', 							'Employee\Media\MediaController@delete')->name('media.delete');
		Route::get('employee/jobs', 							'Employee\Jobs\ContractController@jobs')->name('employee.jobs');
		Route::get('employee/contracts', 						'Employee\Jobs\ContractController@contracts')->name('employee.contracts');
		Route::get('jobs/proposals', 	 						'Employee\Jobs\JobsController@proposals')->name('employee.proposals');
		Route::get('jobs/saved', 	 							'Employee\Jobs\JobsController@saved')->name('employee.saved');
		Route::post('jobs/saveaction', 	 						'Employee\Jobs\JobsController@saveaction')->name('employee.saveaction');
		Route::get('jobs/proposals/job/{id}', 	 				'SearchController@proposals')->name('jobs_proposals');
		Route::post('jobs/proposals/job/{id}', 	 				'SearchController@proposals');
		Route::get('jobs/proposals/details/{id}', 	 			'SearchController@proposal_details')->name('jobs_proposal_details');
		Route::post('jobs/proposals/changeterms/{id}', 	 		'Employee\Jobs\JobsController@changeterms')->name('employee.changeterms');
		Route::get('jobs/invitations/details/{id}', 	 		'Employee\Jobs\JobsController@invites_details')->name('jobs_invites_details');
		Route::post('jobs/decline/{type}/{id}',  				'Employee\Jobs\JobsController@declineaction')->name('employee.jobs.declineaction');
		Route::get('jobs/offer/details/{id}', 	 				'Employee\Jobs\JobsController@offer_details')->name('jobs_offer_details');
		Route::post('jobs/offer/details/{id}', 	 				'Employee\Jobs\JobsController@accept_offer')->name('jobs_offer_accept');
		//contract details
		Route::get('jobs/contract/details/{id}', 	 			'Employee\Jobs\ContractController@contract_details')->name('jobs_contract_details');
		Route::post('jobs/contract/milestone_details', 			'Employee\Jobs\ContractController@milestone_details')->name('jobs_milestone_details');
		Route::post('jobs/contract/submitwork', 				'Employee\Jobs\ContractController@submitwork')->name('employee.submitwork'); 
		Route::get('contracts/details/{id}/leavefeedback',		'Employee\Jobs\ContractController@leavefeedback')->name('employee.contract.leavefeedback');
		Route::post('contracts/details/{id}/leavefeedback',		'Employee\Jobs\ContractController@leavefeedback');
		Route::post('jobs/contract/details/{id}/gettimesheet', 	'Employee\Jobs\ContractController@get_time_sheet')->name('employee.contract.get_time_sheet');
		Route::post('jobs/contract/updatetimesheet/{id}', 	 	'Employee\Jobs\ContractController@updatetimesheet')->name('employee.contract.updatetimesheet');
		//Reports
		Route::get('reports/{main_request}',					'Employee\Jobs\ReportsController@reports_action')->name('employee.reports.main_action');
		Route::get('reports/payments/earnings-history',			'Employee\Jobs\ReportsController@earnings_history')->name('employee.reports.earnings_history');
		//Settings
		Route::get('settings/bankcard-settings',  				'HomeController@bankcard_settings')->name('settings.bankcards');
		Route::post('settings/bankcard-settings',  				'HomeController@bankcard_settings');
		//Loans
		Route::get('loans/settings',  							'Employee\Jobs\LoanController@settings')->name('loans.settings');
		Route::get('loans/loan_request',  						'Employee\Jobs\LoanController@loan_request')->name('loans.request');
		Route::post('loans/loan_request',  						'Employee\Jobs\LoanController@loan_request');
		Route::get('loans/history',  							'Employee\Jobs\LoanController@history')->name('loans.history');
		Route::get('loans/action/reject/{id}',  				'Employee\Jobs\LoanController@reject')->name('loans.reject');
	});
	Route::group(['middleware' => ['auth','employer'], 'prefix' => 'employer'], function() {
		Route::get('profile/start', 							'Employer\Profile\EmployersprofileController@start')->name('employer.profile.start');
	});
	Route::group(['middleware' => ['auth','employer', 'employerstart'], 'prefix' => 'employer'], function() {
		Route::get('profile', 									'Employer\Profile\EmployersprofileController@index')->name('employer.profile'); 
		Route::post('profile/address', 							'Employer\Profile\EmployersprofileController@address')->name('employer.update.address');
		Route::post('profile/update/misc', 						'Employer\Profile\EmployersprofileController@misc')->name('employer.update.misc');
		Route::post('media/store', 								'Employer\Media\MediaController@store')->name('employer.media.store');
		Route::post('media/delete', 							'Employer\Media\MediaController@delete')->name('employer.media.delete');
		// JOB POST
		Route::get('myjobs', 									'Employer\Jobs\JobsController@myjobs')->name('employer.jobs');
		Route::get('mypostings', 								'Employer\Jobs\JobsController@mypostings')->name('employer.mypostings');
		Route::get('contracts', 								'Employer\Jobs\JobsController@contracts')->name('employer.contracts');
		Route::get('contracts/details/{id}', 	 				'Employer\Jobs\ContractController@contract_details')->name('employer.contract_details');
		Route::get('contracts/details/{id}/payment', 	 		'Employer\Jobs\ContractController@contract_payment')->name('employer.contract_payment');
		Route::post('contracts/details/{id}/payment', 	 		'Employer\Jobs\ContractController@contract_payment');
		Route::get('contracts/details/{id}/activate_milestone',	'Employer\Jobs\ContractController@activate_milestone')->name('employer.activate_milestone');
		Route::post('contracts/details/{id}/activate_milestone','Employer\Jobs\ContractController@activate_milestone');
		Route::post('contracts/details/{id}/give_bonus',		'Employer\Jobs\ContractController@give_bonus')->name('employer.give_bonus');
		Route::post('contracts/details/{id}/end_contract',		'Employer\Jobs\ContractController@end_contract')->name('employer.contract.end_contract');
		Route::post('contracts/details/{id}/pause_contract',	'Employer\Jobs\ContractController@pause_contract')->name('employer.contract.pause_contract');
		Route::post('contracts/details/{id}/resume_contract',	'Employer\Jobs\ContractController@resume_contract')->name('employer.contract.resume_contract');
		Route::get('contracts/details/{id}/leavefeedback',		'Employer\Jobs\ContractController@leavefeedback')->name('employer.contract.leavefeedback');
		Route::post('contracts/details/{id}/leavefeedback',		'Employer\Jobs\ContractController@leavefeedback');
		Route::post('contracts/details/{id}/gettimesheet', 	 	'Employer\Jobs\ContractController@get_time_sheet')->name('employer.contract.get_time_sheet');
		
		Route::get('myjobs/postgig', 							'Employer\Jobs\JobsController@postgig')->name('employer.job.add');
		Route::post('myjobs/addgig', 							'Employer\Jobs\JobsController@addjob')->name('employer.job.addjob');
		Route::get('myjobs/postshift', 							'Employer\Jobs\JobsController@postshift')->name('employer.shift.add');
		Route::get('myjobs/edit/{job_id}', 						'Employer\Jobs\JobsController@jobedit')->name('employer.jobs.edit');
		Route::post('myjobs/edit/{job_id}', 					'Employer\Jobs\JobsController@jobedit'); 
		Route::get('myjobs/repost/{job_id}', 					'Employer\Jobs\JobsController@repost')->name('employer.jobs.repost'); 
		Route::get('myjobs/detail/{job_id}/{subrequest}', 		'Employer\Jobs\JobsController@mainaction')->name('employer.jobs.mainaction');
		Route::get('myjobs/detail/{job_id}/users/{user_id}',	'Employer\Jobs\JobsController@mainaction_user')->name('employer.jobs.mainaction.user');
		Route::post('myjobs/close/{job_id}', 					'Employer\Jobs\JobsController@jobclose')->name('employer.jobs.close');
		Route::post('myjobs/delete/{job_id}', 					'Employer\Jobs\JobsController@jobdelete')->name('employer.jobs.delete');
		
		Route::post('myjobs/addsavefreelancer', 				'Employer\Jobs\JobsController@addsavefreelancer')->name('employer.jobs.addsavefreelancer');
		Route::post('myjobs/getsavefreelancer', 				'Employer\Jobs\JobsController@getsavefreelancer')->name('employer.jobs.getsavefreelancer'); 
		Route::post('myjobs/removesavefreelancer',				'Employer\Jobs\JobsController@removesavefreelancer')->name('employer.jobs.removesavefreelancer'); 
		Route::post('myjobs/shortlistaction/{job_id}', 			'Employer\Jobs\JobsController@shortlistaction')->name('employer.jobs.shortlistaction'); 
		Route::post('myjobs/archiveaction/{job_id}', 			'Employer\Jobs\JobsController@archiveaction')->name('employer.jobs.archiveaction'); 
		Route::post('myjobs/invitefreelancer/{job_id}', 		'Employer\Jobs\JobsController@invitefreelancer')->name('employer.jobs.inviteaction');
		Route::post('myjobs/decline/{type}/{job_id}',  			'Employer\Jobs\JobsController@declineaction')->name('employer.jobs.declineaction');
		Route::post('myjobs/detail/{job_id}/sendmessage',		'MessageController@sendmessageToEmployee')->name('employer.jobs.sendmessageToEmployee');
		
		Route::get('myjobs/offer/details/{offer_id}',			'Employer\Jobs\JobsController@offerdetails')->name('employer.jobs.viewoffer');
		Route::get('myjobs/offer/edit/{offer_id}',				'Employer\Jobs\JobsController@editoffer')->name('employer.jobs.editoffer');
		Route::post('myjobs/offer/edit/{offer_id}',				'Employer\Jobs\JobsController@editoffer');
		Route::get('myjobs/offer/create/{job_id}/{user_id}',	'Employer\Jobs\JobsController@sendoffer')->name('employer.jobs.sendoffer');
		Route::post('myjobs/offer/create/{job_id}/{user_id}',	'Employer\Jobs\JobsController@sendoffer');  
		Route::get('myjobs/offer/create/{user_id}',				'Employer\Jobs\JobsController@createoffer')->name('employer.jobs.createoffer');
		Route::post('myjobs/offer/create/{user_id}',			'Employer\Jobs\JobsController@createoffer'); 
		Route::post('myjobs/offer/getofferdetails/{user_id}',	'Employer\Jobs\JobsController@getofferdetails')->name('employer.jobs.getofferdetails');
		
		Route::get('myjobs/offer/more-hiring/{offer_id}', 		'Employer\Jobs\JobsController@morehiring')->name('employer.jobs.morehiring');
		Route::post('myjobs/offer/more-hiring/{offer_id}',		'Employer\Jobs\JobsController@morehiring');
		//settings controller
		Route::get('settings/deposit-methods', 					'Employer\Profile\SettingsController@deposit_methods')->name('employer.profile.settings.deposit_methods');
		Route::post('settings/addcard', 						'Employer\Profile\SettingsController@addCard')->name('employer.profile.settings.addcard');
		Route::get('settings/set_primary_card/{id}',			'Employer\Profile\SettingsController@set_primary_card')->name('employer.profile.settings.setpriamry_card');
		Route::get('settings/remove_card/{id}',					'Employer\Profile\SettingsController@remove_card')->name('employer.profile.settings.remove_card');
		//Reports
		Route::get('reports/billing-history', 					'Employer\Jobs\ReportsController@billing_history')->name('employer.reports.billing_history');
	});
	Route::group(['middleware' => ['auth','master'],'namespace' => 'master','prefix'=>'master'], function () {
		Route::get('/dashboard', 								'DashboardController@index')->name('master.dashboard');
		//Setting 
		Route::get('settings/skills', 							'SkillsController@index')->name('master.skills');
		Route::get('settings/skills/list', 						'SkillsController@list')->name('master.skills.list');
		Route::post('settings/skills/edit', 					'SkillsController@edit')->name('master.skills.edit');
		Route::post('settings/skills/add', 						'SkillsController@add')->name('master.skills.add');
		Route::post('settings/skills/update', 					'SkillsController@update')->name('master.skills.update');
		Route::get('settings/skills/delete/{id}', 				'SkillsController@delete')->name('master.skills.delete');
		
		Route::get('settings/industries', 						'IndustriesController@index')->name('master.industries'); 
		Route::post('settings/industries/edit', 				'IndustriesController@edit')->name('master.industries.edit');
		Route::post('settings/industries/add', 					'IndustriesController@add')->name('master.industries.add');
		Route::post('settings/industries/update', 				'IndustriesController@update')->name('master.industries.update');
		Route::get('settings/industries/delete/{id}', 			'IndustriesController@delete')->name('master.industries.delete');

		Route::get('settings/industries/{id}/subcategories', 	    	'IndustriesController@subcategories')->name('master.industries.subcategories');
		Route::post('settings/industries/{id}/subcategories/add',   	'IndustriesController@subcategories_add')->name('master.industries.subcategories.add');
		Route::post('settings/industries/subcategories/edit',  			'IndustriesController@subcategories_edit')->name('master.industries.subcategories.edit');
		Route::post('settings/industries/subcategories/update',			'IndustriesController@subcategories_update')->name('master.industries.subcategories.update');
		Route::get('settings/industries/subcategories/delete/{sub_id}', 'IndustriesController@subcategories_delete')->name('master.industries.subcategories.delete');
		
		Route::get('settings/questions', 						'QuestionsController@index')->name('master.questions');
		Route::post('settings/questions/add', 					'QuestionsController@add')->name('master.questions.add');
		Route::post('settings/questions/edit', 					'QuestionsController@edit')->name('master.questions.edit');
		Route::post('settings/questions/update', 				'QuestionsController@update')->name('master.questions.update');
		Route::get('settings/index', 							'SettingsController@index')->name('master.settings.index');
		Route::post('settings/update', 							'SettingsController@update')->name('master.settings.update');
		Route::get('/members/employees', 						'MembersController@employees')->name('master.members.employees');
		Route::get('/members/employees/list', 					'MembersController@employeeslist')->name('master.employees.employeeslist');
		Route::get('/members/employees/{id}', 					'MembersController@show')->name('master.members.show');
		Route::get('/members/employees/{id}/loans', 			'MembersController@loans')->name('master.members.loans');
		Route::post('/members/employees/{id}/loans', 			'MembersController@loans');
		Route::post('/members/employees/{id}/loans_setting', 	'MembersController@loans_setting')->name('master.members.loans_setting');
		Route::post('/members/employees/{id}/loans_action', 	'MembersController@loans_action')->name('master.members.loans.action');
		Route::post('document/add/note', 						'MembersController@addnote')->name('document.add.note');
		Route::post('document/get/note', 						'MembersController@getnote')->name('document.get.note');
		Route::post('/members/employees/action',				'MembersController@action')->name('master.members.employees.action'); 
		Route::get('/members/employers', 						'MembersController@employers')->name('master.members.employers');
		Route::get('/members/employers/list',					'MembersController@employerslist')->name('master.employers.employerslist');
		Route::get('/members/employers/{id}', 					'MembersController@employershow')->name('master.members.employer.show');
		Route::post('/members/employers/action',				'MembersController@action')->name('master.members.employer.action');
		//Notification
		Route::get('/notifications',							'NotificationController@index')->name('master.notifications.index');
		Route::get('/notifications/markasread/{id}',			'NotificationController@markasread')->name('master.notifications.markasread');
		Route::get('/notifications/action/{id}',				'NotificationController@action')->name('master.notifications.action');
	});