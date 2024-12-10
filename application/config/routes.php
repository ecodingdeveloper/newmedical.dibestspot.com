<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'home';
$route['doctor-preview/(:any)'] = 'home/doctor_preview/$1';
$route['clinic-preview/(:any)'] = 'home/clinic_preview/$1';
$route['doctors-search'] = 'home/doctors_search';
$route['doctors-searchmap'] = 'home/doctors_searchmap';

//warembrace routes
$route['mywarmembrace/home'] = 'mywarmembrace';
$route['mywarmembrace/webinar-ipregistration'] = 'mywarmembrace/registration';
$route['mywarmembrace/intended-parents'] = 'mywarmembrace/intended_parents';
$route['mywarmembrace/sprogram'] = 'mywarmembrace/sprogram';
$route['mywarmembrace/waiting-room'] = 'mywarmembrace/waiting_room';
$route['mywarmembrace/ip-live-webinar']= 'mywarmembrace/ip_live_webinar';
$route['mywarmembrace/sprogram-video']= 'mywarmembrace/sprogram_video';


//$route['mywarmembrace-home']='mywarmembrace';


$route['book-package/(:any)']='book_package/book/$1';
$route['book-service/(:any)']='book_service/book/$1';

$route['patients-search'] = 'home/patients_search';

$route['schedule-timings'] = 'schedule_timings';
$route['my-patients'] = 'my_patients';
$route['mypatient-preview/(:any)'] = 'my_patients/mypatient_preview/$1';
$route['add-prescription/(:any)'] = 'my_patients/add_prescription/$1';
$route['edit-prescription/(:any)/(:any)'] = 'my_patients/edit_prescription/$1/$1';
$route['print-prescription/(:any)'] = 'my_patients/print_prescription/$1';
$route['print-medical-records/(:any)'] = 'my_patients/print_medical_records/$1';
$route['edit-medical-records/(:any)/(:any)'] = 'my_patients/edit_medical_records/$1/$1';
$route['add-billing/(:any)'] = 'my_patients/add_billing/$1';
$route['edit-billing/(:any)/(:any)'] = 'my_patients/edit_billing/$1/$1';
$route['print-billing/(:any)'] = 'my_patients/print_billing/$1';
$route['patient-preview/(:any)'] = 'my_patients/mypatient_preview/$1';
$route['invoice-view/(:any)'] = 'invoice/view/$1';
$route['orders-details/(:any)'] = 'pharmacy/orders_details/$1';
$route['invoice-products-view/(:any)'] = 'invoice/product_view/$1';
$route['invoice-products-print/(:any)'] = 'invoice/product_invoice_print/$1';
$route['invoice-print/(:any)'] = 'invoice/invoice_print/$1';
$route['change-password'] = 'profile/change_password';
$route['social-media'] = 'profile/social_media';
$route['favourites'] = 'dashboard/favourites';
$route['reviews'] = 'dashboard/reviews';
$route['reviews-list'] = 'dashboard/patient_reviews';
$route['invoice/checkout/(:any)'] = 'invoice/invoice_checkout/$1';


$route['terms-conditions'] = 'home/terms_conditions';
$route['privacy-policy'] = 'home/privacy_policy';


$route['book-appoinments/(:any)'] = 'book_appoinments/book/$1';
$route['checkout'] = 'book_appoinments/checkout';
$route['appoinment-success/(:any)'] = 'book_appoinments/payment_sucess/$1';

$route['outgoing-video-call/(:any)'] = 'appoinments/outgoingvideocall/$1';
$route['outgoing-call/(:any)'] = 'appoinments/outgoingcall/$1';
$route['incoming-call/(:any)'] = 'appoinments/incomingcall/$1';
$route['incoming-video-call/(:any)'] = 'appoinments/incomingvideocall/$1';



$route['signin'] = 'signin';
$route['register'] = 'signin/register';
$route['sign-out'] = 'signin/sign_out';
$route['forgot-password'] = 'signin/forgot_password';
$route['activate/(:any)'] = 'signin/activate/$1';
$route['reset/(:any)'] = 'signin/reset/$1';
$route['profile'] = 'profile';


$route['admin'] = 'admin/login';
$route['admin/edit_email_template/(:any)'] = 'admin/email_template/edit_email_template';
$route['admin/email_template/add'] = 'admin/email_template/add_email_template';
$route['admin/edit/(:any)'] = 'admin/email_template/edit';
$route['admin/language/pages/(:any)'] = 'admin/language/app_keywords';


//Blog
$route['blog'] = 'blog/home';
$route['blog/blog-details/(:any)'] = 'blog/home/blog_details/$1';
$route['blog/pending-post'] = 'blog/post/pending_post';
$route['blog/add-post'] = 'blog/post/add_post';
$route['blog/edit-post/(:any)'] = 'blog/post/edit_post/$1';



// API

$route['api/config'] = 'api/api/config_list';
$route['api/home'] = 'api/api/home';
$route['api/signin'] = 'api/api/signin';
$route['api/signup'] = 'api/api/signup';
$route['api/doctor_list'] = 'api/api/doctor_list';
$route['api/specialization_list'] = 'api/api/specialization_list';
$route['api/doctor_preview'] = 'api/api/doctor_preview';
$route['api/clinic_preview'] = 'api/api/clinic_preview';
$route['api/doctor_details'] = 'api/api/doctor_details';
$route['api/update_patient_profile'] = 'api/api/update_patient_profile';
$route['api/update_doctor_profile'] = 'api/api/update_doctor_profile';
$route['api/change_password'] = 'api/api/change_password';
$route['api/patient_profile'] = 'api/api/patient_profile';
$route['api/master'] = 'api/api/master';
$route['api/forgot_password'] = 'api/api/forgot_password';
$route['api/available_time_slots'] = 'api/api/available_time_slots';
$route['api/add_schedule'] = 'api/api/add_schedule';
$route['api/get_schedule'] = 'api/api/get_schedule';
$route['api/doctor_search'] = 'api/api/doctor_search';
$route['api/get_token'] = 'api/api/get_token';
$route['api/appoinments_calculation'] = 'api/api/appoinments_calculation';
$route['api/checkout'] = 'api/api/checkout';
$route['api/appointments_list'] = 'api/api/appointments_list';
$route['api/appointments_history'] = 'api/api/appointments_history';
$route['api/prescription_list'] = 'api/api/prescription_list';
$route['api/prescription_insert'] = 'api/api/prescription_insert';
$route['api/prescription_detail'] = 'api/api/prescription_detail';
$route['api/prescription_delete'] = 'api/api/prescription_delete';
$route['api/medical_records_list'] = 'api/api/medical_records_list';
$route['api/upload_medical_record'] = 'api/api/upload_medical_record';
$route['api/billing_list'] = 'api/api/billing_list';
$route['api/my_patients'] = 'api/api/my_patients';
$route['api/make_outgoing_call'] = 'api/api/make_outgoing_call';
$route['api/make_incoming_call'] = 'api/api/make_incoming_call';
$route['api/end_call'] = 'api/api/end_call';
$route['api/add_favourities'] = 'api/api/add_favourities';
$route['api/favourities_list'] = 'api/api/favourities_list';
$route['api/add_reviews'] = 'api/api/add_reviews';
$route['api/my_doctors'] = 'api/api/my_doctors';
$route['api/change_appoinments_status'] = 'api/api/change_appoinments_status';
$route['api/reviews_list'] = 'api/api/reviews_list';
$route['api/dashboard_count'] = 'api/api/dashboard_count';
$route['api/language_list'] = 'api/api/language_list';
$route['api/language_keywords'] = 'api/api/language_keywords';
$route['api/chat_users'] = 'api/api/chat_users';
$route['api/conversation'] = 'api/api/conversation';
$route['api/send_message'] = 'api/api/send_message';
$route['api/logout'] = 'api/api/logout';

$route['api/hospital_doctor_list'] = 'api/api/hospital_doctor_list';
$route['api/hospital_doctor_delete'] = 'api/api/hospital_doctor_delete';
$route['api/assigned_to_doctor'] = 'api/api/assigned_to_doctor';
$route['api/pharmacy_invoice_details'] = 'api/api/pharmacy_invoice_details';
$route['api/pharmacy_invoice'] = 'api/api/pharmacy_invoice';
$route['api/assigned_to_doctor'] = 'api/api/assigned_to_doctor'; 
$route['api/generateotp'] = 'api/api/generateotp'; 


// Pharmacy APIs

$route['api/search_pharmacy'] = 'api/api/search_pharmacy';
$route['api/get_phamacy_details'] = 'api/api/get_phamacy_details';
$route['api/pharmacy_product_and_category_list'] = 'api/api/pharmacy_product_and_category_list';
$route['api/order_list'] = 'api/api/order_list';
$route['api/order_details'] = 'api/api/order_details';
$route['api/placeOrder'] = 'api/api/placeOrder';
$route['api/dashboard_order_list'] = 'api/api/dashboard_order_list';
$route['api/pharmacy_product_search'] = 'api/api/pharmacy_product_search';


// Pharmacy

$route['doctor-pharmacy-search'] = 'home/pharmacy_search_bydoctor';
// products and pharmacy
$route['products-list'] = 'products/products_list';


$route['product-details/(:any)'] = 'products/product_details/$1';
$route['payment-sucess'] = 'cart/payment_sucess';

$route['cart-list'] = 'cart/cart_list';

//$route['cart-checkout'] = 'cart/checkout';
//$route['cart-checkout'] = 'cart/ad_shipping_details';

$route['add-product'] = 'pharmacy/add_product';
$route['product-list'] = 'pharmacy/product_list';
$route['orders-list'] = 'pharmacy/orders_list';
$route['edit-product/(:any)'] = 'pharmacy/edit_product/$1';
$route['cart-list'] = 'cart/cart_list';
$route['cart-checkout'] = 'cart/checkout';

$route['patient-share-prescription/(:any)'] = 'my_patients/patient_share_prescription/$1';

$route['doctor-pharmacy-search'] = 'home/pharmacy_search_bydoctor';
$route['payment-sucess'] = 'cart/payment_sucess';


$route['maps'] = 'dashboard/maps';
$route['maps/(:any)'] = 'dashboard/get_direction/$1';


$route['labs-search']='home/labs_search';

// Lab
$route['lab-test'] = 'lab';
$route['lab-tests/(:any)'] = 'home/lab_tests/$1';
$route['lab-appointments'] = 'lab/appointments';
$route['api/lab_list'] = 'api/api/lab_list';
$route['api/add_labtest'] = 'api/api/add_labtest';
$route['api/lab_testlist'] = 'api/api/lab_testlist';
$route['api/lab_appointment_testlist'] = 'api/api/lab_appointment_testlist';
$route['api/edit_labtest'] = 'api/api/edit_labtest';
$route['api/lab_appointments'] = 'api/api/lab_appointments';
$route['api/labresult_upload'] = 'api/api/labresult_upload';
$route['api/checkout_lab'] = 'api/api/checkout_lab';
$route['api/lab_dashboard'] = 'api/api/lab_dashboard';


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
