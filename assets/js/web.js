$(document).ready(function () {
  var tz = jstz.determine();
  var timezone = tz.name();
  $.post(
    base_url + "ajax/set_timezone",
    { timezone: timezone },
    function (res) {
      // console.log(res);
    }
  );
  $.post(
    base_url + "ajax/set_timezone",
    { timezone: timezone },
    function (res) {
      // console.log(res);
    }
  );

  $.post(base_url + "ajax/currency_rate", function (res) {
    //console.log(res);
  });
  //Message module we need to show user status so updating timestam whem page reloading
  $.post(base_url + "ajax/update_user_status", function (res) {
    //console.log(res);
  });
});

function clear_all(id = null) {
  $.ajax({
    type: "POST",
    data: { id: id },
    url: base_url + "dashboard/notification_update",
    dataType: "json",
    success: function (response) {
      // return false;
      if (response.status == 200) {
        toastr.success("Notifications clear");
        setTimeout(function () {
          location.reload(true);
        }, 1000);
        location.reload(true);
      } else {
        toastr.error(response.msg);
      }
    },
  });
}

function change_language(lang, language) {
  $.post(
    base_url + "ajax/set_language",
    { lang: lang, language: language },
    function (res) {
      window.location.reload();
    }
  );
}

function user_currency(code) {
  if (code != "") {
    $.ajax({
      type: "POST",
      url: base_url + "ajax/add_user_currency",
      data: { code: code },
      dataType: "json",
      success: function (response) {
        if (response.success) {
          location.reload();
        } else {
          toastr.error(response.msg);
          setTimeout(function () {
            location.reload(true);
          }, 1000);
        }
      },
    });
  }
}

if (modules == "signin") {
  function change_role(role) {
    if (role == 1) {
      // Doctor
      $("#role_type").html(lang_doctor);
      $("#doc_btn").addClass("active");
      $("#pat_btn").removeClass("active");
      $("#hos_btn").removeClass("active");
      $("#lab_btn").removeClass("active");
      $("#pha_btn").removeClass("active");
      $("#cli_btn").removeClass("active");
      $("#mci_div").show();
      $("#gst_div").hide();
      $("#upload_div").show();

      $(".doctor_code").show();
      //$('#register_form')[0].reset();
      $(".other_code").hide();
    }
    if (role == 2) {
      // Patient
      $("#role_type").html(lang_patient);
      $("#pat_btn").addClass("active");
      $("#doc_btn").removeClass("active");
      $("#hos_btn").removeClass("active");
      $("#lab_btn").removeClass("active");
      $("#pha_btn").removeClass("active");
      $("#cli_btn").removeClass("active");
      $("#mci_div").hide();
      $("#gst_div").hide();
      $("#upload_div").hide();

      $(".doctor_code").hide();
      // $('#register_form')[0].reset();
      $(".other_code").show();
    }
    if (role == 3) {
      //  Hospital/Clinic
      $("#role_type").html("Hospital/Clinic");
      $("#hos_btn").addClass("active");
      $("#doc_btn").removeClass("active");
      $("#pat_btn").removeClass("active");
      $("#lab_btn").removeClass("active");
      $("#pha_btn").removeClass("active");
      $("#cli_btn").removeClass("active");
      $("#mci_div").hide();
      $("#gst_div").show();
      $("#upload_div").show();

      $(".doctor_code").hide();
      //$('#register_form')[0].reset();
      $(".other_code").show();
    }
    if (role == 4) {
      // Labs
      $("#role_type").html("Lab");
      $("#lab_btn").addClass("active");
      $("#doc_btn").removeClass("active");
      $("#pat_btn").removeClass("active");
      $("#hos_btn").removeClass("active");
      $("#pha_btn").removeClass("active");
      $("#cli_btn").removeClass("active");
      $("#mci_div").hide();
      $("#gst_div").hide();
      $("#upload_div").hide();

      $(".doctor_code").hide();
      //$('#register_form')[0].reset();
      $(".other_code").show();
    }
    if (role == 5) {
      // pharamacy
      $("#role_type").html("Pharmacy");
      $("#doc_btn").removeClass("active");
      $("#pat_btn").removeClass("active");
      $("#hos_btn").removeClass("active");
      $("#lab_btn").removeClass("active");
      $("#pha_btn").addClass("active");
      $("#cli_btn").removeClass("active");
      $("#mci_div").hide();
      $("#gst_div").hide();
      $("#upload_div").hide();

      $(".doctor_code").hide();
      //$('#register_form')[0].reset();
      $(".other_code").hide();
    }
    if (role == 6) {
      // clinic
      $("#role_type").html("Clinic");
      $("#doc_btn").removeClass("active");
      $("#pat_btn").removeClass("active");
      $("#hos_btn").removeClass("active");
      $("#lab_btn").removeClass("active");
      $("#pha_btn").removeClass("active");
      $("#cli_btn").addClass("active");
      $("#mci_div").hide();
      $("#gst_div").hide();
      $("#upload_div").hide();

      $(".doctor_code").hide();
      //$('#register_form')[0].reset();
      $(".other_code").hide();
    }
    $("#role").val(role);
    //$('#register_form')[0].reset();
  }
  function resend_otp() {
    var mobileno = $("#mobileno").val();
    var country_code = $("#country_code").val();

    $.ajax({
      url: base_url + "Signin/sendotp",
      data: {
        mobileno: mobileno,
        country_code: country_code,
        otpcount: "2",
      },
      //contentType: "application/json; charset=utf-8",
      dataType: "text",
      method: "post",
      beforeSend: function () {
        $(".otp_load").html(
          '<div class="spinner-border text-light" role="status"></div>'
        );
      },
      success: function (res) {
        $(".otp_load").html(
          '<a class="forgot-link" onclick="resend_otp()"  href="javascript:void(0);" id="resendotp">' +
            lg_resend_otp +
            "</a>"
        );
        var obj = JSON.parse(res);

        if (obj.status === 200) {
          $(".OTP").show();
          toastr.success(obj.msg);
        } else if (obj.status === 500) {
          toastr.error(obj.msg);
        } else {
          toastr.error(obj.msg);
        }
      },
    });
  }
}
if (modules == "home") {
  if (pages == "index") {
    $("#search_button").click(function () {
      var search_keywords = $.trim($("#search_keywords").val());
      var search_location = $.trim($("#search_location").val());
      if (search_keywords != "" && search_location != "") {
        window.location.href =
          base_url +
          "doctors-search?location=" +
          search_location +
          "&keywords=" +
          search_keywords;
      } else if (search_keywords != "") {
        window.location.href =
          base_url + "doctors-search?keywords=" + search_keywords;
      } else if (search_location != "") {
        window.location.href =
          base_url + "doctors-search?location=" + search_location;
      } else {
        toastr.warning(lg_please_enter_ke);
      }
    });

    function search_locations() {
      $(".location_result").html("");
      var search_location = $.trim($("#search_location").val());
      if (search_location != "") {
        $.ajax({
          type: "POST",
          url: base_url + "home/search_location",
          data: "search_location=" + search_location,
          success: function (data) {
            // return false;
            if (data.length) {
              var obj = jQuery.parseJSON(data);
              var html = "";

              if (obj.location != null) {
                $(obj.location).each(function () {
                  html +=
                    '<div class="keyword-search"><a href="' +
                    base_url +
                    "doctors-search?location=" +
                    this.location +
                    '">' +
                    lg_location2 +
                    "  - " +
                    this.location +
                    "</a></div>";
                });
              }

              $(".location_result").html(html);
            } else {
              $(".location_result").html("<b>" + lg_no_city_found + "</b>");
            }
          },
        });
      } else {
        $(".location_result").html("");
      }
    }

    function search_keyword() {
      $(".keywords_result").html("");
      var search_keywords = $.trim($("#search_keywords").val());
      if (search_keywords != "") {
        $.ajax({
          type: "POST",
          url: base_url + "home/search_keywords",
          data: "search_keywords=" + search_keywords,
          success: function (data) {
            // return false;
            if (data.length) {
              var obj = jQuery.parseJSON(data);
              var html = "";

              if (obj.specialist != null) {
                $(obj.specialist).each(function () {
                  html +=
                    '<div class="keyword-search"><a href="' +
                    base_url +
                    "doctors-search?keywords=" +
                    this.specialization +
                    '">' +
                    lg_speciality +
                    "  - " +
                    this.specialization +
                    "</a></div>";
                });
              }

              if (obj.doctor != null) {
                $(obj.doctor).each(function () {
                  html +=
                    '<div class="keyword-search"><a href="' +
                    base_url +
                    "doctors-search?keywords=" +
                    this.first_name +
                    '"><div class="keyword-img"><img src="' +
                    this.profileimage +
                    '" class="img-responsive"></div>' +
                    lg_dr +
                    " " +
                    this.first_name +
                    " " +
                    this.last_name +
                    "</a><small>" +
                    lg_specialist +
                    " -" +
                    this.speciality +
                    "</small></div>";
                });

                $(".keywords_result").html(html);
              } else {
                var html = "<b>" + lg_no_doctors_foun1 + "</b>";
              }

              $(".keywords_result").html(html);
            } else {
              $(".keywords_result").html("<b>" + lg_no_doctors_foun1 + "</b>");
            }
          },
        });
      } else {
        $(".keyword_result").html("");
      }
    }
  }
  if (pages == "index" || pages == "doctor_preview") {
    function add_favourities(doctor_id) {
      $.post(
        base_url + "home/add_favourities",
        { doctor_id: doctor_id },
        function (data) {
          var obj = JSON.parse(data);

          if (obj.status === 200) {
            $("#favourities_" + doctor_id).addClass("fav-btns");
            toastr.success(obj.msg);
          } else if (obj.status === 204) {
            toastr.warning(obj.msg);
          } else if (obj.status === 201) {
            $("#favourities_" + doctor_id).removeClass("fav-btns");
            toastr.success(obj.msg);
          } else {
            $("#favourities_" + doctor_id).removeClass("fav-btns");
          }
        }
      );
    }
  }
}
$(document).ready(function () {
  //     $.ajax({
  //         type: "GET",
  //         url: base_url + "ajax/get_country_code",
  //         data: {id: $(this).val()},
  //         beforeSend: function () {
  //             $('#country_code').find("option:eq(0)").html("Please wait..");
  //         },
  //         success: function (data) {
  //             /*get response as json */
  //             $('#country_code').find("option:eq(0)").html("Select Country Code");
  //             var obj = jQuery.parseJSON(data);
  //             $(obj).each(function ()
  //             {
  //                 var option = $('<option />');
  //                 option.attr('value', this.value).text(this.label);
  //                 $('#country_code').append(option);
  //             });

  //             $('#country_code').val(country_code);
  //             /*ends */

  //         }
  //     });
  //signin
  if (modules == "signin") {
    if (pages == "register") {
      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_country_code",
        data: { id: $(this).val() },
        beforeSend: function () {
          $("#country_code").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#country_code").find("option:eq(0)").html(lg_select_country_);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#country_code").append(option);
          });

          $("#country_code").val(country_code);
          /*ends */
        },
      });

      $(".OTP").hide();
      $("#resendotp").hide();
      $("#sendotp").on("click", function () {
        var mobileno = $("#mobileno").val();
        var country_code = $("#country_code").val();
        if (mobileno == "") {
          toastr.error(lg_please_enter_va4);
        } else {
          $.ajax({
            url: base_url + "Signin/sendotp",
            data: {
              mobileno: mobileno,
              country_code: country_code,
              otpcount: "1",
            },

            dataType: "text",
            method: "post",
            beforeSend: function () {
              $(".otp_load").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $(".otp_load").html(
                '<a class="forgot-link" onclick="resend_otp()"  href="javascript:void(0);" id="resendotp">' +
                  lg_resend_otp +
                  "</a>"
              );

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                $(".OTP").show();
                $("#resendotp").show();
                toastr.success(obj.msg);
              } else if (obj.status === 500) {
                toastr.error(obj.msg);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
        }
      });

      $("#register_form").validate({
        rules: {
          first_name: {
            required: true,
            minlength: 3,
            maxlength: 150,
            text_spaces_only: true,
          },
          last_name: {
            required: true,
            maxlength: 150,
            text_spaces_only: true,
          },
          country_code: {
            required: true,
          },
          mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
              url: base_url + "signin/check_mobileno",
              type: "post",
              data: {
                mobileno: function () {
                  return $("#mobileno").val();
                },
              },
            },
          },
          email: {
            required: true,
            email: true,
            remote: {
              url: base_url + "signin/check_email",
              type: "post",
              data: {
                email: function () {
                  return $("#email").val();
                },
              },
            },
          },
          password: {
            required: true,
            minlength: 6,
            maxlength: 100,
          },
          confirm_password: {
            required: true,
            equalTo: "#password",
            maxlength: 100,
          },
          agree_statement: { required: true },
        },
        messages: {
          first_name: {
            required: lg_please_enter_yo,
            minlength: lg_first_name_shou,
            maxlength: lg_first_name_shou_max,
          },
          last_name: {
            required: lg_please_enter_yo1,
            maxlength: lg_last_name_shoul_max,
          },
          country_code: {
            required: lg_select_country_,
          },
          mobileno: {
            required: lg_please_enter_mo,
            maxlength: lg_number_should_b1,
            minlength: lg_number_should_b,
            digits: lg_digits_are_only,
            remote: lg_your_mobile_no_,
          },
          email: {
            required: lg_please_enter_em,
            email: lg_please_enter_va1,
            remote: lg_your_email_addr1,
          },
          password: {
            required: lg_please_enter_pa,
            minlength: lg_your_password_m,
            maxlength: lg_password_max_length,
          },
          confirm_password: {
            required: lg_please_enter_co,
            equalTo: lg_your_password_d,
            maxlength: lg_confirm_password_max_length,
          },
          agree_statement: {
            required: lg_please_accept_t,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "signin/signup",
            data: $("#register_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#register_btn").attr("disabled", true);
              $("#register_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#register_btn").attr("disabled", false);
              $("#register_btn").html(lg_signup);
              var obj = JSON.parse(res);

              if (obj.status === 200) {
                $("#register_form")[0].reset();
                window.location.href = base_url + "signin";
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    }

    if (pages == "index") {
      $("#signin_form").validate({
        rules: {
          email: "required",
          password: {
            required: true,
            minlength: 6,
          },
        },
        messages: {
          email: lg_please_enter_em1,
          password: {
            required: lg_please_enter_pa,
            minlength: lg_your_password_m,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "signin/is_valid_login",
            data: $("#signin_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#signin_btn").attr("disabled", true);
              $("#signin_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#signin_btn").attr("disabled", false);
              $("#signin_btn").html(lg_signin);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                window.location.href = base_url + "dashboard";
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    }
    if (pages == "forgot_password") {
      $("#reset_password").validate({
        rules: {
          resetemail: {
            required: true,
            email: true,
            remote: {
              url: base_url + "signin/check_resetemail",
              type: "post",
              data: {
                resetemail: function () {
                  return $("#resetemail").val();
                },
              },
            },
          },
        },
        messages: {
          resetemail: {
            required: lg_please_enter_em,
            email: lg_please_enter_va1,
            remote: lg_your_email_addr,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "signin/reset_password",
            data: $("#reset_password").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#reset_pwd").attr("disabled", true);
              $("#reset_pwd").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#reset_pwd").attr("disabled", false);
              $("#reset_pwd").html(lg_reset_password);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                $("#reset_password")[0].reset();
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "home";
                }, 5000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    }

    if (pages == "change_password") {
      $(document).ready(function () {
        $("#change_password").validate({
          rules: {
            password: {
              required: true,
              minlength: 6,
              maxlength: 100,
            },
            confirm_password: {
              required: true,
              equalTo: "#password",
              maxlength: 100,
            },
          },
          messages: {
            password: {
              required: lg_please_enter_pa,
              minlength: lg_your_password_m,
              maxlength: lg_password_max_length,
            },
            confirm_password: {
              required: lg_please_enter_co,
              equalTo: lg_your_password_d,
              maxlength: lg_confirm_password_max_length,
            },
          },
          submitHandler: function (form) {
            $.ajax({
              url: base_url + "signin/update_password",
              data: $("#change_password").serialize(),
              type: "POST",
              beforeSend: function () {
                $("#update_pwd").attr("disabled", true);
                $("#update_pwd").html(
                  '<div class="spinner-border text-light" role="status"></div>'
                );
              },
              success: function (res) {
                $("#update_pwd").attr("disabled", false);
                $("#update_pwd").html(lg_confirm3);
                var obj = JSON.parse(res);

                if (obj.status === 200) {
                  $("#change_password")[0].reset();
                  toastr.success(obj.msg);
                  setTimeout(function () {
                    window.location.href = base_url + "home";
                  }, 5000);
                } else {
                  toastr.error(obj.msg);
                }
              },
            });
            return false;
          },
        });
      });
    }
  }

  if (
    (modules == "home" ||
      modules == "patient" ||
      modules == "doctor" ||
      modules == "hospital" ||
      modules == "lab" ||
      modules == "pharmacy" ||
      modules == "ecommerce") &&
    (pages == "doctor_profile" ||
      pages == "patient_profile" ||
      pages == "hospital_profile" ||
      pages == "lab_profile" ||
      pages == "doctors_search" ||
      pages == "doctors_searchmap" ||
      pages == "patients_search" ||
      pages == "add_branch" ||
      pages == "labs_searchmap" ||
      pages == "pharmacy_profile" ||
      pages == "checkout" ||
      pages == "pharmacy_search_bydoctor" ||
      pages == "add_doctor")
  ) {
    // reset patient profile upload form
    $(document)
      .off("click", ".profile_image_popup_close")
      .on("click", ".profile_image_popup_close", function () {
        $(".avatar-form")[0].reset();
      });

    $.ajax({
      type: "GET",
      url: base_url + "ajax/get_country_code",
      data: { id: $(this).val() },
      beforeSend: function () {
        $("#country_code").find("option:eq(0)").html(lg_please_wait);
      },
      success: function (data) {
        /*get response as json */
        $("#country_code").find("option:eq(0)").html(lg_select_country_);
        var obj = jQuery.parseJSON(data);
        $(obj).each(function () {
          var option = $("<option />");
          option.attr("value", this.value).text(this.label);
          $("#country_code").append(option);
        });

        $("#country_code").val(country_code);

        /*ends */
      },
    });

    if (
      pages != "checkout" ||
      (modules == "ecommerce" && pages == "checkout")
    ) {
      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_country",
        data: { id: $(this).val() },
        beforeSend: function () {
          $("#country").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#country").find("option:eq(0)").html(lg_select_country);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#country").append(option);
          });

          $("#country").val(country);

          /*ends */
        },
      });

      $.ajax({
        type: "POST",
        url: base_url + "ajax/get_state",
        data: { id: country },
        beforeSend: function () {
          $("#state option:gt(0)").remove();
          $("#city option:gt(0)").remove();
          $("#state").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#state").find("option:eq(0)").html(lg_select_state);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#state").append(option);
          });
          $("#state").val(state);
          /*ends */
        },
      });

      $.ajax({
        type: "POST",
        url: base_url + "ajax/get_city",
        data: { id: state },
        beforeSend: function () {
          $("#city option:gt(0)").remove();
          $("#city").find("option:eq(0)").html(lg_please_wait);
        },

        success: function (data) {
          /*get response as json */
          $("#city").find("option:eq(0)").html(lg_select_city);

          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#city").append(option);
          });
          $("#city").val(city);
          /*ends */
        },
      });

      /*Get the state list */

      $("#country").change(function () {
        $.ajax({
          type: "POST",
          url: base_url + "ajax/get_state",
          data: { id: $(this).val() },
          beforeSend: function () {
            $("#state option:gt(0)").remove();
            $("#city option:gt(0)").remove();
            $("#state").find("option:eq(0)").html(lg_please_wait);
          },
          success: function (data) {
            /*get response as json */
            $("#state").find("option:eq(0)").html(lg_select_state);
            var obj = jQuery.parseJSON(data);
            $(obj).each(function () {
              var option = $("<option />");
              option.attr("value", this.value).text(this.label);
              $("#state").append(option);
            });

            /*ends */
          },
        });
      });

      /*Get the state list */

      $("#state").change(function () {
        $.ajax({
          type: "POST",
          url: base_url + "ajax/get_city",
          data: { id: $(this).val() },
          beforeSend: function () {
            $("#city option:gt(0)").remove();
            $("#city").find("option:eq(0)").html(lg_please_wait);
          },

          success: function (data) {
            /*get response as json */
            $("#city").find("option:eq(0)").html(lg_select_city);

            var obj = jQuery.parseJSON(data);
            $(obj).each(function () {
              var option = $("<option />");
              option.attr("value", this.value).text(this.label);
              $("#city").append(option);
            });

            /*ends */
          },
        });
      });
    }

    if (
      pages == "doctor_profile" ||
      pages == "doctors_search" ||
      pages == "doctors_searchmap"
    ) {
      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_specialization",
        data: { id: $(this).val() },
        beforeSend: function () {
          $("#specialization").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#specialization").find("option:eq(0)").html(lg_select_speciali1);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#specialization").append(option);
          });

          $("#specialization").val(specialization);

          /*ends */
        },
      });
    }

    if (pages == "pharmacy_profile") {
      var maxDate = $("#maxDate").val();
      $("#dob").datepicker({
        startView: 2,
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
        endDate: maxDate,
      });

      $("#pharmacy_profile_form").validate({
        rules: {
          // pharmacy_name: "required",
          first_name: {
            required: true,
            maxlength: 50,
          },
          last_name: {
            required: true,
            maxlength: 50,
          },
          // mobileno: "required",
          mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
          },
          gender: "required",
          dob: "required",
          address1: { required: true, minlength: 5, maxlength: 100 },
          // address2: "required",
          country: "required",
          state: "required",
          city: "required",
          postal_code: {
            required: true,
            minlength: 4,
            maxlength: 7,
            digits: true,
          },
          home_delivery: "required",
          hrsopen: "required",
        },
        messages: {
          // pharmacy_name: lg_please_enter_ph,
          first_name: {
            required: lg_please_enter_yo,
          },
          last_name: {
            required: lg_please_enter_yo1,
          },
          gender: lg_select_gender,
          dob: lg_dob_is_require,
          address1: {
            required: lg_please_enter_yo3,
            minlength: "Minimum length should be 5 characters",
            maxlength: "Maximum length should be 100 characters",
          },
          // address2: lg_please_enter_yo4,
          country: lg_please_select_c,
          state: lg_please_select_s,
          city: lg_please_select_c1,
          postal_code: {
            required: lg_please_enter_po,
            maxlength: lg_please_enter_va2,
            minlength: lg_please_enter_va2,
            digits: lg_please_enter_va2,
          },
          home_delivery: "Please select home delivery available yes/no",
          hrsopen: "Select 24hrs Open yes/no",
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "profile/update_pharmacy_profile",
            data: $("#pharmacy_profile_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#save_btns").attr("disabled", true);
              $("#save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#save_btn").attr("disabled", false);
              $("#save_btn").html(lg_save_changes);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "dashboard";
                }, 5000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    }

    if (pages == "doctor_profile") {
      /*Get the country list */
      $("#doctor_profile_form").validate({
        rules: {
          first_name: "required",
          last_name: "required",
          mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
              url: base_url + "profile/check_mobileno",
              type: "post",
              data: {
                mobileno: function () {
                  return $("#mobileno").val();
                },
              },
            },
          },
          gender: "required",
          dob: "required",
          clinic_address: { required: true, maxlength: 100 },
          clinic_address2: { maxlength: 100 },
          clinic_city: {
            required: true,
            SpecCharValidate: true,
            maxlength: 50,
          },
          clinic_state: {
            required: true,
            SpecCharValidate: true,
            maxlength: 50,
          },
          clinic_country: {
            required: true,
            SpecCharValidate: true,
            maxlength: 50,
          },
          clinic_postal: { digits: true },
          address1: { required: true, maxlength: 100 },
          address2: { maxlength: 100 },
          // address2: "required",
          country: "required",
          state: "required",
          city: "required",
          postal_code: {
            required: true,
            minlength: 4,
            maxlength: 7,
            digits: true,
          },
          price_type: "required",
          amount: {
            required: function (element) {
              if (
                $("input[name='price_type']:checked").val() === "Custom Price"
              ) {
                return true;
              } else {
                return false;
              }
            },
            number: true,
            min: 1,
            maxlength: 6,
          },
          services: { required: true, SpecCharValidate: true },
          specialization: "required",

          "hospital_name[]": { SpecCharValidate: true, maxlength: 50 },
          "designation[]": { SpecCharValidate: true, maxlength: 50 },
          "awards[]": { SpecCharValidate: true, maxlength: 50 },
          "memberships[]": { SpecCharValidate: true, maxlength: 50 },
          "registrations[]": { SpecCharValidate: true, maxlength: 50 },
        },
        messages: {
          first_name: lg_please_enter_yo,
          last_name: lg_please_enter_yo1,
          mobileno: {
            required: lg_please_enter_mo,
            maxlength: lg_please_enter_va,
            minlength: lg_please_enter_va,
            digits: lg_please_enter_va,
            remote: lg_your_mobile_no_,
          },
          gender: lg_please_select_g,
          dob: lg_please_enter_yo2,
          address1: {
            required: lg_please_enter_yo3,
            maxlength: "Maximum length should be 100 characters",
          },
          address2: { maxlength: "Maximum length should be 100 characters" },
          clinic_address: {
            required: lg_please_enter_yo3,
            maxlength: "Maximum length should be 100 characters",
          },
          clinic_address2: {
            maxlength: "Maximum length should be 100 characters",
          },
          clinic_city: {
            required: "Please enter Clinic city",
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          clinic_state: {
            required: "Please enter Clinic State",
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          clinic_country: {
            required: lg_please_select_c,
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          clinic_postal: { digits: lg_please_enter_va2 },
          // address2: lg_please_enter_yo4,
          country: lg_please_select_c,

          state: lg_please_select_s,
          city: lg_please_select_c1,
          postal_code: {
            required: lg_please_enter_po,
            maxlength: lg_please_enter_va2,
            minlength: lg_please_enter_va2,
            digits: lg_please_enter_va2,
          },
          price_type: lg_please_select_p,
          amount: {
            required: lg_please_enter_am,
            digits: lg_please_enter_va3,
            min: lg_please_enter_va3,
            maxlength: "Max length should be 6 digits only",
          },
          services: {
            required: lg_please_enter_se,
            SpecCharValidate: "No Special Characters/Numbers Allowed",
          },
          specialization: lg_please_select_s1,

          "hospital_name[]": {
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          "designation[]": {
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          "awards[]": {
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          "memberships[]": {
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
          "registrations[]": {
            SpecCharValidate: "No Special Characters/Numbers Allowed",
            maxlength: "Maximum length should be 50 characters",
          },
        },
        submitHandler: function (form) {
          /*Fields from Ajax Validation*/
          var inputerr = 0;
          var checkerr = 0;
          var checkerr1 = 0;
          $(".inputcls").map(function () {
            if ($.trim($(this).val()) == "") {
              $(this).attr("style", "border-color:red");
              $(".select2-selection").attr("style", "border-color:red");
              inputerr++;
            } else {
              $(this).removeAttr("style");
              $(".select2-selection").removeAttr("style");
            }
          });
          if (inputerr > 0) {
            toastr.error(lg_please_enter_va5);
            return false;
          } else {
            $("input[name='services[]']").each(function () {
              var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
              var services = $("input[name='services[]']").val();
              if (!characterReg.test(services)) {
                checkerr++;
              }
              if (services.length > 30) {
                checkerr1++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_no_special_char);
              return false;
            }
            if (checkerr1 > 0) {
              toastr.error(lg_degree_length_s);
              return false;
            }

            // For degree Name Special char and number validation start
            $("input[name='degree[]']").each(function () {
              var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
              var degree = $("input[name='degree[]']").val();
              if (!characterReg.test(degree)) {
                checkerr++;
              }
              if (degree.length > 30) {
                checkerr1++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_no_special_char);
              return false;
            }
            if (checkerr1 > 0) {
              toastr.error(lg_degree_length_s);
              return false;
            }
            // For Degree name Special char and number validation end

            // For Institue name Special char and number validation
            $("input[name='institute[]']").each(function () {
              var characterReg1 = /^\s*[a-zA-Z,\s]+\s*$/;
              var institute = $("input[name='institute[]']").val();
              if (!characterReg1.test(institute)) {
                checkerr++;
              }
              if (institute.length > 100) {
                checkerr1++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_no_special_char);
              return false;
            }
            if (checkerr1 > 0) {
              toastr.error(lg_institute_chara);
              return false;
            }
          }
          /*Fields from Ajax Validation*/

          $.ajax({
            url: base_url + "profile/update_doctor_profile",
            data: $("#doctor_profile_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#save_btn").attr("disabled", true);
              $("#save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#save_btn").attr("disabled", false);
              $("#save_btn").html(lg_save_changes);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "dashboard";
                }, 5000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });

      $(document).on("click", ".days_check", function () {
        if ($(this).is(":checked") == true) {
          $(".eachdays").attr("disabled", "disabled");
          $(".eachdayfromtime").attr("disabled", "disabled");
          $(".eachdaytotime").attr("disabled", "disabled");
          $(".eachdays").prop("checked", false);
          $(".eachdays").removeAttr("style");
          $(".eachdayfromtime").removeAttr("style");
          $(".eachdaytotime").removeAttr("style");
        } else {
          $(".eachdays").removeAttr("disabled");
          $(".eachdayfromtime").removeAttr("disabled");
          $(".eachdaytotime").removeAttr("disabled");
          $(".daysfromtime_check").val("");
          $(".daystotime_check").val("");
          $(".daysfromtime_check").removeAttr("style");
          $(".daystotime_check").removeAttr("style");
        }
      });
    }

    if (pages == "patient_profile") {
      var maxDate = $("#maxDate").val();
      $("#dob").datepicker({
        startView: 2,
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
        endDate: maxDate,
      });

      $("#patient_profile_form").validate({
        rules: {
          first_name: "required",
          last_name: "required",
          mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
              url: base_url + "profile/check_mobileno",
              type: "post",
              data: {
                mobileno: function () {
                  return $("#mobileno").val();
                },
              },
            },
          },
          country_code: {
            required: true,
          },
          gender: "required",
          dob: "required",
          // blood_group: "required",
          address1: {
            required: true,
            address_validation: true,
            maxlength: 500,
          },
          address2: {
            address_validation: true,
            maxlength: 500,
          },
          // address2: "required",
          country: "required",
          state: "required",
          city: "required",
          postal_code: {
            required: true,
            minlength: 4,
            maxlength: 7,
            digits: true,
          },
        },
        messages: {
          first_name: lg_please_enter_yo,
          last_name: lg_please_enter_yo1,
          mobileno: {
            required: lg_please_enter_mo,
            maxlength: lg_please_enter_va,
            minlength: lg_please_enter_va,
            digits: lg_please_enter_va,
            remote: lg_your_mobile_no_,
          },
          country_code: {
            required: lg_please_select_c_code,
          },
          gender: lg_please_select_g,
          dob: lg_please_enter_yo2,
          // blood_group: lg_please_select_b,
          // address1: lg_please_enter_yo3,
          // address2: lg_please_enter_yo4,
          address1: {
            required: lg_pers_info_address_req,
            address_validation: lg_pers_info_address_val,
            maxlength: lg_enter_address_max,
          },
          address2: {
            address_validation: lg_pers_info_address_val,
            maxlength: lg_enter_address_max,
          },
          country: lg_please_select_c,
          state: lg_please_select_s,
          city: lg_please_select_c1,
          postal_code: {
            required: lg_please_enter_po,
            maxlength: lg_please_enter_va2,
            minlength: lg_please_enter_va2,
            digits: lg_please_enter_va2,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "profile/update_patient_profile",
            data: $("#patient_profile_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#save_btn").attr("disabled", true);
              $("#save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#save_btn").attr("disabled", false);
              $("#save_btn").html(lg_save_changes);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "dashboard";
                }, 5000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    }
  }

  if (modules == "home" && pages == "pharmacy_search_bydoctor") {
    $(document).on("click", ".pharmacy_profile_btn", function () {
      $("body").removeClass("modal-open");
      var pharmacy_id = $(this).data("pharmacy-id");
      $.ajax({
        //url:  base_url +'my_patients/get_phamacy_details',
        url: base_url + "home/get_phamacy_details",
        type: "POST",
        data: { pharmacy_id: pharmacy_id },
        success: function (response) {
          // console.log(response);
          var obj = JSON.parse(response);
          //console.log(obj);
          if (obj.status === 200) {
            if (obj.data.length >= 1) {
              var html = "";
              $(obj.data).each(function () {
                var pharmacy_name =
                  this.pharmacy_name != "" && this.pharmacy_name != null
                    ? this.pharmacy_name
                    : "";
                var first_name =
                  this.first_name != "" && this.first_name != null
                    ? this.first_name
                    : "";
                var last_name =
                  this.last_name != "" && this.last_name != null
                    ? this.last_name
                    : "";
                var profileimage =
                  this.profileimage != "" && this.profileimage != null
                    ? this.profileimage
                    : "";
                var phonecode =
                  this.phonecode != "" && this.phonecode != null
                    ? this.phonecode
                    : "";
                var mobileno =
                  this.mobileno != "" && this.mobileno != null
                    ? this.mobileno
                    : "";
                var address1 =
                  this.address1 != "" && this.address1 != null
                    ? this.address1
                    : "";
                var address2 =
                  this.address2 != "" && this.address2 != null
                    ? this.address2
                    : "";
                var city =
                  this.city != "" && this.city != null ? this.city : "";
                var statename =
                  this.statename != "" && this.statename != null
                    ? this.statename
                    : "";
                var country =
                  this.country != "" && this.country != null
                    ? this.country
                    : "";
                var pharmacy_opens_at =
                  this.pharamcy_opens_at != "" && this.pharamcy_opens_at != null
                    ? this.pharamcy_opens_at
                    : "";
                var home_delivery =
                  this.home_delivery != "" && this.home_delivery != null
                    ? this.home_delivery
                    : "";
                var hrsopen =
                  this.hrsopen != "" && this.hrsopen != null
                    ? this.hrsopen
                    : "";

                html += '<div class="card-body">';
                html +=
                  '<center><img src="' +
                  base_url +
                  profileimage +
                  '" class="img-fluid" alt="' +
                  pharmacy_name +
                  '" title="' +
                  pharmacy_name +
                  '" ></center><br />';
                html += '<table class="table table-bordered table-hover">';
                html += "<tr>";
                html += "<td>Pharmacy name</td>";
                html += "<td>" + pharmacy_name + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>User name</td>";
                html += "<td>" + first_name + " " + last_name + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Mobile no</td>";
                html += "<td>(+" + phonecode + ") " + mobileno + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Address 1</td>";
                html += "<td>" + address1 + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Address 2</td>";
                html += "<td>" + address2 + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>City</td>";
                html += "<td>" + city + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>State name</td>";
                html += "<td>" + statename + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Country</td>";
                html += "<td>" + country + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Pharmacy opens at</td>";
                html += "<td>" + pharmacy_opens_at + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>Home delivery avalable</td>";
                html += "<td>" + home_delivery + "</td>";
                html += "</tr>";
                html += "<tr>";
                html += "<td>24Hrs Open</td>";
                html += "<td>" + hrsopen + "</td>";
                html += "</tr>";
                html += "</table>";
                html += "</div>";
              });

              $(".view_pharmacy_details").html(html);
            } else {
              var html = "<p>" + lg_pharmacy_detail + "</p>";
              $(".view_pharmacy_details").html(html);
            }
          } else {
            var html = "<p>" + lg_pharmacy_detail + "</p>";
            $(".view_pharmacy_details").html(html);
          }
        },
      });
    });
  }

  if (modules == "ecommerce" || pages == "products_list_by_pharmacy") {
    function search_subcategory(subcategory) {
      reset_products();
      $("#subcategory").val(subcategory);

      get_products(0);
    }

    function reset_products() {
      $("#category").val("");
      $("#keywords").val("");
      $("#subcategory").val("");

      get_products(0);
    }

    if (pages == "index" || pages == "products_list_by_pharmacy") {
      get_products(0);
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      get_products(1);
    });

    function cart_lists() {
      $.get(base_url + "cart/cart_lists", function (data) {
        $("#loading").hide();
        var obj = jQuery.parseJSON(data);
        $(".cart_lists").html(obj.cart_list);
        $(".checkout_cart_lists").html(obj.checkout_html);
        $(".checkout_cart_html").html(obj.checkout_cart_html);
        $("#cart_pay_btn").hide();
        if (obj.cart_count == 1) {
          $("#cart_pay_btn").show();
        }
      });
    }

    if (pages != "checkout_package") {
      cart_lists();
    }

    function remove_cart(id) {
      $.ajax({
        url: base_url + "cart/remove_cart",
        type: "POST",
        data: { id: id },
        cache: false,
        success: function (data) {
          cart_count();
          cart_lists();
        },
      });
    }
    function increment_quantity(cart_id) {
      var inputQuantityElement = $("#input-quantity-" + cart_id);
      var newQuantity = parseInt($(inputQuantityElement).val()) + 1;
      save_to_db(cart_id, newQuantity);
    }
    function decrement_quantity(cart_id) {
      var inputQuantityElement = $("#input-quantity-" + cart_id);
      if ($(inputQuantityElement).val() > 1) {
        var newQuantity = parseInt($(inputQuantityElement).val()) - 1;
        save_to_db(cart_id, newQuantity);
      }
    }
    function save_to_db(cart_id, new_quantity) {
      var inputQuantityElement = $("#input-quantity-" + cart_id);
      $.ajax({
        url: base_url + "cart/update_cart",
        data: "cart_id=" + cart_id + "&new_quantity=" + new_quantity,
        type: "post",
        success: function (response) {
          cart_count();
          cart_lists();
        },
      });
    }
    function cart_count() {
      $.get(base_url + "cart/cart_count", function (data) {
        var obj = jQuery.parseJSON(data);
        $(".cart_count").html(obj.cart_count);
      });
    }
  }

  if (modules == "doctor" && pages == "social_media") {
    /*Get the country list */
    $("#doctor_social_media").validate({
      rules: {
        facebook: { required: true, validFBurl: true, maxlength: 255 },
        twitter: { required: true, validTwitterUrl: true, maxlength: 255 },
        instagram: { required: true, validInstagramUrl: true, maxlength: 255 },
        pinterest: { required: true, validPinterestUrl: true, maxlength: 255 },
        linkedin: { required: true, validLinkedinUrl: true, maxlength: 255 },
        youtube: { required: true, validYoutubeUrl: true, maxlength: 255 },
      },
      messages: {
        facebook: {
          required: "Enter FB URL",
          validFBurl: "Enter the valid FB URL",
        },
        twitter: {
          required: "Enter Twitter URL",
          validTwitterUrl: "Enter the valid Twitter URL",
        },
        instagram: {
          required: "Enter the Instagram URL",
          validInstagramUrl: "Enter the valid Instagram URL",
        },
        pinterest: {
          required: "Enter the Pinterest URL",
          validPinterestUrl: "Enter the valid Pinterest URL",
        },
        linkedin: {
          required: "Enter the Pinterest URL",
          validLinkedinUrl: "Enter the valid Linkedin URL",
        },
        youtube: {
          required: "Enter the Youtube URL",
          validYoutubeUrl: "Enter the valid Youtube URL",
        },
      },
      submitHandler: function (form) {
        $.ajax({
          url: base_url + "profile/update_social_media",
          data: $("#doctor_social_media").serialize(),
          type: "POST",
          beforeSend: function () {
            $("#save_btn").attr("disabled", true);
            $("#save_btn").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (res) {
            $("#save_btn").attr("disabled", false);
            $("#save_btn").html(lg_save_changes);

            var obj = JSON.parse(res);

            if (obj.status === 200) {
              toastr.success(obj.msg);
              setTimeout(function () {
                location.reload(true);
              }, 1000);
            } else {
              toastr.error(obj.msg);
            }
          },
        });
        return false;
      },
    });
  }

  if (
    ((modules == "doctor" || modules == "patient") &&
      (pages == "doctor_profile" || pages == "patient_profile")) ||
    pages == "doctors_search" ||
    pages == "patients_search"
  ) {
    if (pages == "doctor_profile") {
      /*Get the country list */
      $("#doctor_profile_form").validate({
        rules: {
          first_name: "required",
          last_name: "required",
          mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
              url: base_url + "profile/check_mobileno",
              type: "post",
              data: {
                mobileno: function () {
                  return $("#mobileno").val();
                },
              },
            },
          },
          gender: "required",
          dob: "required",
          address1: "required",
          // address2: "required",
          country: "required",
          state: "required",
          city: "required",
          postal_code: {
            required: true,
            minlength: 4,
            maxlength: 7,
            digits: true,
          },
          price_type: "required",
          amount: {
            required: function (element) {
              if (
                $("input[name='price_type']:checked").val() === "Custom Price"
              ) {
                return true;
              } else {
                return false;
              }
            },
            digits: true,
            min: 1,
          },
          services: "required",
          specialization: "required",
          "degree[]": "required",
          "institute[]": "required",
          "year_of_completion[]": "required",
        },
        messages: {
          first_name: lg_please_enter_yo,
          last_name: lg_please_enter_yo1,
          mobileno: {
            required: lg_please_enter_mo,
            maxlength: lg_please_enter_va,
            minlength: lg_please_enter_va,
            digits: lg_please_enter_va,
            remote: lg_your_mobile_no_,
          },
          gender: lg_please_select_g,
          dob: lg_please_enter_yo2,
          address1: lg_please_enter_yo3,
          // address2: lg_please_enter_yo4,
          country: lg_please_select_c,
          state: lg_please_select_s,
          city: lg_please_select_c1,
          postal_code: {
            required: lg_please_enter_po,
            maxlength: lg_please_enter_va2,
            minlength: lg_please_enter_va2,
            digits: lg_please_enter_va2,
          },
          price_type: lg_please_select_p,
          amount: {
            required: lg_please_enter_am,
            digits: lg_please_enter_va3,
            min: lg_please_enter_va3,
          },
          services: lg_please_enter_se,
          specialization: lg_please_select_s1,
          "degree[]": lg_please_enter_de,
          "institute[]": lg_please_enter_in,
          "year_of_completion[]": lg_please_enter_ye,
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "profile/update_doctor_profile",
            data: $("#doctor_profile_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#save_btn").attr("disabled", true);
              $("#save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#save_btn").attr("disabled", false);
              $("#save_btn").html(lg_save_changes);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "dashboard";
                }, 5000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });

      $(document).on("click", ".days_check", function () {
        if ($(this).is(":checked") == true) {
          $(".eachdays").attr("disabled", "disabled");
          $(".eachdayfromtime").attr("disabled", "disabled");
          $(".eachdaytotime").attr("disabled", "disabled");
          $(".eachdays").prop("checked", false);
          $(".eachdays").removeAttr("style");
          $(".eachdayfromtime").removeAttr("style");
          $(".eachdaytotime").removeAttr("style");
        } else {
          $(".eachdays").removeAttr("disabled");
          $(".eachdayfromtime").removeAttr("disabled");
          $(".eachdaytotime").removeAttr("disabled");
          $(".daysfromtime_check").val("");
          $(".daystotime_check").val("");
          $(".daysfromtime_check").removeAttr("style");
          $(".daystotime_check").removeAttr("style");
        }
      });
    }

    if (pages == "patient_profile") {
      var maxDate = $("#maxDate").val();
      $("#dob").datepicker({
        startView: 2,
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
        endDate: maxDate,
      });

      $("#patient_profile_form").validate({
        rules: {
          first_name: "required",
          last_name: "required",
          mobileno: {
            required: true,
            minlength: 7,
            maxlength: 12,
            digits: true,
            remote: {
              url: base_url + "profile/check_mobileno",
              type: "post",
              data: {
                mobileno: function () {
                  return $("#mobileno").val();
                },
              },
            },
          },
          gender: "required",
          dob: "required",
          blood_group: "required",
          address1: "required",
          address2: "required",
          country: "required",
          state: "required",
          city: "required",
          postal_code: {
            required: true,
            minlength: 4,
            maxlength: 7,
            digits: true,
          },
        },
        messages: {
          first_name: lg_please_enter_yo,
          last_name: lg_please_enter_yo1,
          mobileno: {
            required: lg_please_enter_mo,
            maxlength: lg_please_enter_va,
            minlength: lg_please_enter_va,
            digits: lg_please_enter_va,
            remote: lg_your_mobile_no_,
          },
          gender: lg_please_select_g,
          dob: lg_please_enter_yo2,
          blood_group: lg_please_select_b,
          address1: lg_please_enter_yo3,
          address2: lg_please_enter_yo4,
          country: lg_please_select_c,
          state: lg_please_select_s,
          city: lg_please_select_c1,
          postal_code: {
            required: lg_please_enter_po,
            maxlength: lg_please_enter_va2,
            minlength: lg_please_enter_va2,
            digits: lg_please_enter_va2,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "profile/update_patient_profile",
            data: $("#patient_profile_form").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#save_btn").attr("disabled", true);
              $("#save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#save_btn").attr("disabled", false);
              $("#save_btn").html(lg_save_changes);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "dashboard";
                }, 5000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    }
  }
});

if (modules == "doctor" && pages == "doctor_profile") {
  function delete_clinic_image(id) {
    $.ajax({
      url: base_url + "profile/delete_clinic_image",
      data: { id: id },
      type: "POST",
      beforeSend: function () {},
      success: function (res) {
        var obj = JSON.parse(res);

        if (obj.status === 200) {
          toastr.success(obj.msg);
          location.reload(true);
        } else {
          toastr.error(lg_something_went_1);
        }
      },
    });
  }
}

if (modules == "doctor" && pages == "reviews") {
  function add_reply(id) {
    $("#review_div_" + id).show();
  }

  function create_reply(id) {
    var review_id = $("#review_id_" + id).val();
    var reply = $("#reply_text_" + id).val();
    if (reply != "") {
      $.ajax({
        type: "POST",
        url: base_url + "dashboard/add_review_reply",
        data: { review_id: review_id, reply: reply }, // <--- THIS IS THE CHANGE
        beforeSend: function () {
          $("#reply_btn_" + id).attr("disabled", true);
          $("#reply_btn_" + id).html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (data) {
          $("#reply_btn_" + id).attr("disabled", false);
          $("#reply_btn_" + id).html(lg_submit);
          var obj = JSON.parse(data);
          if (obj.status === 200) {
            toastr.success(obj.msg);
            setTimeout(function () {
              location.reload(true);
            }, 1000);
          } else {
            toastr.error(obj.msg);
          }
        },
        error: function () {
          alert(lg_error_posting_f);
        },
      });
    } else {
      toastr.error(lg_reply_should_no);
    }
  }

  function delete_reply(id) {
    if (confirm(lg_are_you_sure_wa3)) {
      $.ajax({
        type: "POST",
        url: base_url + "dashboard/delete_reply",
        data: { id: id }, // <--- THIS IS THE CHANGE
        beforeSend: function () {},
        success: function (data) {
          var obj = JSON.parse(data);
          if (obj.status === 200) {
            toastr.success(obj.msg);
            setTimeout(function () {
              location.reload(true);
            }, 1000);
          } else {
            toastr.error(obj.msg);
          }
        },
        error: function () {
          alert(lg_error_posting_f);
        },
      });
    } else {
      return false;
    }
  }
}

if (modules == "home") {
  if (pages == "doctors_search") {
    console.log(modules);
    console.log(pages);
    search_doctor(0);

    function reset_doctor() {
      // location.reload(true);
      window.location = base_url + "doctors-search";
    }

    function reset_clinic() {
      // location.reload(true);
      window.location = base_url + "doctors-search?type=6";
    }

    $(document).ready(function () {
      $(".specialization").prop("checked", false);
      $(".gender").prop("checked", false);
    });

    function search_doctor(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var specialization = $(".specialization:checked")
        .map(function () {
          return this.value;
        })
        .get()
        .join(", ");

      var gender = $(".gender:checked")
        .map(function () {
          return this.value;
        })
        .get()
        .join(", ");

      console.log(specialization);

      var order_by = $("#orderby").val();
      var page = $("#page_no_hidden").val();
      //var gender = $("#gender"). val();
      var role = $("#role").val();
      var login_role = $("#login_role").val();
      var appointment_type = $("#appointment_type").val();
      var city = $("#city").val();
      var state = $("#state").val();
      var country = $("#country").val();
      var keywords = $("#keywords").val();
      var queryString = window.location.search;
      var urlParams = new URLSearchParams(queryString);
      var clinic_id = urlParams.get("id");

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "home/search_doctor",
        type: "POST",
        data: {
          appointment_type: appointment_type,
          gender: gender,
          specialization: specialization,
          order_by: order_by,
          page: page,
          role: role,
          login_role: login_role,
          keywords: keywords,
          get_id: clinic_id,
          city: city,
          citys: citys,
          state: state,
          country: country,
        },
        beforeSend: function () {
          $("#loading").show();
        },
        complete: function () {
          $("#loading").hide();
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            var obj = $.parseJSON(response);
            console.log(obj.data);
            if (obj.data.length >= 1) {
              var html = "";

              $(obj.data).each(function () {
                var services = "";

                if (this.services != null && this.services.length != 0) {
                  var service = this.services.split(",");
                  for (var i = 0; i < service.length; i++) {
                    services += "<span>" + service[i] + "</span>";
                  }
                }

                var clinic_images = "";

                var clinic_images_file = $.parseJSON(this.clinic_images);
                $.each(clinic_images_file, function (key, item) {
                  var userid = item.user_id;
                  clinic_images +=
                    '<li> <a href="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" data-fancybox="gallery"> <img src="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" alt="Feature"> </a> </li>';
                });
                var fullname = "";
                if (role == 1) {
                  fullname =
                    lg_dr + " " + this.first_name + " " + this.last_name;
                } else {
                  fullname =
                    this.clinicname != ""
                      ? this.clinicname
                      : this.first_name + " " + this.last_name;
                }

                html +=
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  '<div class="doc-info-left">' +
                  '<div class="doctor-img">' +
                  '<a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  '<img src="' +
                  this.profileimage +
                  '" class="img-fluid" alt="User Image">' +
                  "</a>" +
                  "</div>" +
                  '<div class="doc-info-cont">' +
                  '<h4 class="doc-name"><a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  fullname +
                  "</a></h4>";
                if (role == 1) {
                  html +=
                    '<h5 class="doc-department"><img src="' +
                    this.specialization_img +
                    '" class="img-fluid" alt="Speciality">' +
                    this.speciality +
                    "</h5>";
                }
                html += '<div class="rating">';
                for (var j = 1; j <= 5; j++) {
                  if (j <= this.rating_value) {
                    html += '<i class="fas fa-star filled"></i>';
                  } else {
                    html += '<i class="fas fa-star"></i>';
                  }
                }
                html +=
                  '<span class="d-inline-block average-rating">(' +
                  this.rating_count +
                  ")</span>" +
                  "</div>" +
                  '<div class="clinic-details">' +
                  '<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</p>" +
                  ' <ul class="clinic-gallery">' +
                  clinic_images +
                  "</ul>" +
                  "</div>";
                if (role != 6) {
                  html += '<div class="clinic-services">' + services + "</div>";
                }
                html +=
                  "</div>" +
                  "</div>" +
                  '<div class="doc-info-right">' +
                  '<div class="clini-infos">' +
                  "<ul>" +
                  '<li><i class="far fa-comment"></i>' +
                  this.rating_count +
                  " " +
                  lg_feedback +
                  "</li>" +
                  '<li><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</li>" +
                  '<li><i class="far fa-money-bill-alt"></i> ' +
                  this.amount +
                  " </li>" +
                  "</ul>" +
                  "</div>" +
                  '<div class="clinic-booking">' +
                  '<a class="view-pro-btn" href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  lg_view_profile +
                  "</a>";

                if (
                  (login_role != 5) &
                  (login_role != 4) &
                  (login_role != 1) &
                  (login_role != 6)
                ) {
                  html +=
                    '<a class="apt-btn" href="' +
                    base_url +
                    "book-appoinments/" +
                    this.username +
                    '">' +
                    lg_book_appointmen +
                    "</a>";
                }
                html += "</div>" + "</div>" + "</div>" + "</div>" + "</div>";
              });

              if (obj.current_page_no == 1) {
                $("#doctor-list").html(html);
              } else {
                $("#doctor-list").append(html);
              }
            } else {
              if (role != 6) {
                var html =
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  "<p>" +
                  lg_no_doctors_foun +
                  "</p>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              } else {
                var html =
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  "<p>" +
                  lg_no_clinic_found +
                  "</p>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              }
              $("#doctor-list").html(html);
            }

            var minimized_elements = $("h4.minimize");
            minimized_elements.each(function () {
              var t = $(this).text();
              if (t.length < 100) return;
              $(this).html(
                t.slice(0, 100) +
                  '<span>... </span><a href="#" class="more">' +
                  lg_more +
                  "</a>" +
                  '<span style="display:none;">' +
                  t.slice(100, t.length) +
                  ' <a href="#" class="less">' +
                  lg_less +
                  "</a></span>"
              );
            });

            $(".search-results").html(
              "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
            );
            // $(".widget-title").html(obj.count+' Matches for your search');
            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 3) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_doctor(1);
    });
  }
}

if (modules == "home") {
  if (pages == "package_search") {
    console.log(modules);
    console.log(pages);
    search_package(0);

    function reset_doctor() {
      // location.reload(true);
      window.location = base_url + "doctors-search";
    }

    function reset_clinic() {
      // location.reload(true);
      window.location = base_url + "doctors-search?type=6";
    }

    $(document).ready(function () {
      $(".specialization").prop("checked", false);
      $(".gender").prop("checked", false);
    });

    function search_package(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var specialization = $(".specialization:checked")
        .map(function () {
          return this.value;
        })
        .get()
        .join(", ");

      var gender = $(".gender:checked")
        .map(function () {
          return this.value;
        })
        .get()
        .join(", ");

      console.log(specialization);

      var order_by = $("#orderby").val();
      var page = $("#page_no_hidden").val();
      //var gender = $("#gender"). val();
      var role = $("#role").val();
      var login_role = $("#login_role").val();
      var appointment_type = $("#appointment_type").val();
      var city = $("#city").val();
      var state = $("#state").val();
      var country = $("#country").val();
      var keywords = $("#keywords").val();
      var queryString = window.location.search;
      var urlParams = new URLSearchParams(queryString);
      var clinic_id = urlParams.get("id");

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "home/search_package",
        type: "POST",
        data: {
          appointment_type: appointment_type,
          gender: gender,
          specialization: specialization,
          order_by: order_by,
          page: page,
          role: role,
          login_role: login_role,
          keywords: keywords,
          get_id: clinic_id,
          city: city,
          citys: citys,
          state: state,
          country: country,
        },
        beforeSend: function () {
          $("#loading").show();
        },
        complete: function () {
          $("#loading").hide();
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            // console.log(response);
            var obj = $.parseJSON(response);
            console.log(obj.data);
            if (obj.data.length >= 1) {
              var html = "";

              $(obj.data).each(function (index, data) {
                //console.log(data.id);

                //console.log("Descriptions:", descriptions);
                //console.log("Prices:", prices);

                var services = "";

                if (this.services != null && this.services.length != 0) {
                  var service = this.services.split(",");
                  for (var i = 0; i < service.length; i++) {
                    services += "<span>" + service[i] + "</span>";
                  }
                }

                var clinic_images = "";

                var clinic_images_file = $.parseJSON(this.clinic_images);
                $.each(clinic_images_file, function (key, item) {
                  var userid = item.user_id;
                  clinic_images +=
                    '<li> <a href="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" data-fancybox="gallery"> <img src="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" alt="Feature"> </a> </li>';
                });
                var fullname = "";
                var package_img = this.package_image;
                if (package_img == "uploads/package_image/") {
                  package_img += "no-image.png";
                }
                console.log(package_img);
                fullname = "Package Name : " + this.package_name;

                html +=
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  '<div class="doc-info-left">' +
                  '<div class="doctor-img">' +
                  '<a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  '<img src="' +
                  base_url +
                  package_img +
                  '" class="img-fluid" alt="Package Image">' +
                  "</a>" +
                  "</div>" +
                  '<div class="doc-info-cont">' +
                  '<h4 class="doc-name" style="padding-bottom:15px;"><a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  fullname +
                  "</a></h4>";
                if ((this.accomodation = "Threestar")) {
                  this.accomodation = "3 Star";
                } else {
                  this.accomodation = "5 Star";
                }
                var speciality = "";
                speciality = "Speciality : " + this.speciality;
                var description = "";
                description = "Description : " + this.description;
                html +=
                  '<h5 class="" style="padding-bottom:10px;" >' +
                  description +
                  "</h5>" +
                  '<h5 class="doc-department">' +
                  speciality +
                  "</h5>";

                // html += '<div class="rating">';
                // for (var j = 1; j <= 5; j++) {
                //     if (j <= this.rating_value) {
                //         html += '<i class="fas fa-star filled"></i>';
                //     } else {
                //         html += '<i class="fas fa-star"></i>';
                //     }
                // }
                // html += '<span class="d-inline-block average-rating">(' + this.rating_count + ')</span>' +
                //     '</div>' +
                let arr = data.add_on.split(",");
                let descriptions = [];
                let prices = [];
                //console.log("arr",arr);
                for (let item of arr) {
                  let dashSplit = item.split("-");

                  if (dashSplit.length >= 2) {
                    descriptions.push(dashSplit[0].trim());
                    prices.push(parseInt(dashSplit[1].trim()));
                  }
                }
                html +=
                  '<div class="clinic-details">' +
                  '<p class="doc-location">' +
                  "What's not included : " +
                  this.not_included +
                  "<p>Add-Ons: "+
                  this.add_on+
                  "</p>";
              
                +'<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' +
                  this.destination +
                  "</p>" +
                  // '<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' + this. + '</p>' +
                  ' <ul class="clinic-gallery">' +
                  clinic_images +
                  "</ul>" +
                  "</div>";
                // if (role != 6) {
                //     html += '<div class="clinic-services">' + services + '</div>';
                // }
                html +=
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  '<div class="doc-info-right">' +
                  '<div class="clini-infos">' +
                  "<ul>" +
                  // '<li><i class="far fa-comment"></i>' + this.rating_count + ' ' + lg_feedback + '</li>' +
                  '<li><i class="fas fa-map-marker-alt"></i> ' +
                  this.destination +
                  "</li>" +
                  '<li><i class="far fa-money-bill-alt"></i> ' +
                  this.currency +
                  ' <span class="total-cost-${data.id}">' +
                  this.cost +
                  "</span> </li>" +
                  "</ul>" +
                  "</div>" +
                  '<div class="clinic-booking">';
                // '<a class="view-pro-btn" href="' + base_url + '-preview/' + this.username + '">' + lg_view_profile + '</a>';

                if (
                  (login_role != 5) &
                  (login_role != 4) &
                  (login_role != 1) &
                  (login_role != 6)
                ) {
                  html +=
                    '<a class="apt-btn" id="book-btn-${data.id}" href="' +
                    base_url +
                    "book-package/" +
                    this.id +
                    '">' +
                    "Book Now" +
                    "</a>";
                }
                html += "</div>" + "</div>" + "</div>" + "</div>";
              
               
              });

              if (obj.current_page_no == 1) {
                $("#package-list").html(html);
              } else {
                $("#package-list").append(html);
              }
            } else {
              if (role != 6) {
                var html =
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  "<p>" +
                  lg_no_doctors_foun +
                  "</p>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              } else {
                var html =
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  "<p>" +
                  lg_no_clinic_found +
                  "</p>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              }
              $("#package-list").html(html);
            }

            var minimized_elements = $("h4.minimize");
            minimized_elements.each(function () {
              var t = $(this).text();
              if (t.length < 100) return;
              $(this).html(
                t.slice(0, 100) +
                  '<span>... </span><a href="#" class="more">' +
                  lg_more +
                  "</a>" +
                  '<span style="display:none;">' +
                  t.slice(100, t.length) +
                  ' <a href="#" class="less">' +
                  lg_less +
                  "</a></span>"
              );
            });

            $(".search-results").html(
              "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
            );
            // $(".widget-title").html(obj.count+' Matches for your search');
            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 3) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_package(1);
    });
  }
  if (pages == "service_search") {
    console.log(modules);
    console.log(pages);
    search_service(0);

    function reset_doctor() {
      // location.reload(true);
      window.location = base_url + "doctors-search";
    }

    function reset_clinic() {
      // location.reload(true);
      window.location = base_url + "doctors-search?type=6";
    }

    $(document).ready(function () {
      $(".specialization").prop("checked", false);
      $(".gender").prop("checked", false);
    });

    function search_service(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var specialization = $(".specialization:checked")
        .map(function () {
          return this.value;
        })
        .get()
        .join(", ");

      var gender = $(".gender:checked")
        .map(function () {
          return this.value;
        })
        .get()
        .join(", ");

      console.log(specialization);

      var order_by = $("#orderby").val();
      var page = $("#page_no_hidden").val();
      //var gender = $("#gender"). val();
      var role = $("#role").val();
      var login_role = $("#login_role").val();
      var appointment_type = $("#appointment_type").val();
      var city = $("#city").val();
      var state = $("#state").val();
      var country = $("#country").val();
      var keywords = $("#keywords").val();
      var queryString = window.location.search;
      var urlParams = new URLSearchParams(queryString);
      var clinic_id = urlParams.get("id");

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "home/search_service",
        type: "POST",
        data: {
          appointment_type: appointment_type,
          gender: gender,
          specialization: specialization,
          order_by: order_by,
          page: page,
          role: role,
          login_role: login_role,
          keywords: keywords,
          get_id: clinic_id,
          city: city,
          citys: citys,
          state: state,
          country: country,
        },
        beforeSend: function () {
          $("#loading").show();
        },
        complete: function () {
          $("#loading").hide();
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            // console.log(response);
            var obj = $.parseJSON(response);
            console.log(obj.data);
            if (obj.data.length >= 1) {
              var html = "";

              $(obj.data).each(function (index, data) {
                console.log(data.id);

                var services = "";

                if (this.services != null && this.services.length != 0) {
                  var service = this.services.split(",");
                  for (var i = 0; i < service.length; i++) {
                    services += "<span>" + service[i] + "</span>";
                  }
                }

                var clinic_images = "";

                var clinic_images_file = $.parseJSON(this.clinic_images);
                $.each(clinic_images_file, function (key, item) {
                  var userid = item.user_id;
                  clinic_images +=
                    '<li> <a href="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" data-fancybox="gallery"> <img src="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" alt="Feature"> </a> </li>';
                });
                var fullname = "";

                fullname = "Speciality : " + this.specialization_list;

                html +=
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  '<div class="doc-info-left">' +
                  '<div class="doctor-img" style="display:none;" >' +
                  "<h1>" +
                  "</h1>" +
                  "</div>" +
                  '<div class="doc-info-cont">' +
                  '<h4 class="doc-name" style="padding-bottom:15px;">' +
                  fullname +
                  "</a></h4>";

                var doctor_list = "";
                doctor_list = "Doctor Name : " + this.doctor_list;
                var operation = "";
                operation = "Operation : " + this.operation;
                html +=
                  '<h5 class="" style="padding-bottom:10px;" >' +
                  operation +
                  "</h5>" +
                  '<h5 class="doc-department">' +
                  doctor_list +
                  "</h5>";

                // html += '<div class="rating">';
                // for (var j = 1; j <= 5; j++) {
                //     if (j <= this.rating_value) {
                //         html += '<i class="fas fa-star filled"></i>';
                //     } else {
                //         html += '<i class="fas fa-star"></i>';
                //     }
                // }
                // html += '<span class="d-inline-block average-rating">(' + this.rating_count + ')</span>' +
                //     '</div>' +
                html +=
                  '<div class="clinic-details">' +
                  '<p class="doc-location">' +
                  "Clinic Name : " +
                  this.service_clinic +
                  "</p>" +
                  // '<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' + this.country + '</p>' +
                  // '<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' + this. + '</p>' +
                  ' <ul class="clinic-gallery">' +
                  clinic_images +
                  "</ul>" +
                  "</div>";
                // if (role != 6) {
                //     html += '<div class="clinic-services">' + services + '</div>';
                // }
                html +=
                  "</div>" +
                  "</div>" +
                  '<div class="doc-info-right">' +
                  '<div class="clini-infos">' +
                  "<ul>" +
                  // '<li><i class="far fa-comment"></i>' + this.rating_count + ' ' + lg_feedback + '</li>' +
                  "<li>" +
                  "Country: " +
                  this.country +
                  "</li>" +
                  '<li><i class="fas fa-map-marker-alt"></i> ' +
                  this.city +
                  "</li>" +
                  '<li><i class="far fa-money-bill-alt"></i> ' +
                  "$" +
                  this.service_price +
                  " </li>" +
                  "</ul>" +
                  "</div>" +
                  '<div class="clinic-booking">';
                // '<a class="view-pro-btn" href="' + base_url + '-preview/' + this.username + '">' + lg_view_profile + '</a>';

                if (
                  (login_role != 5) &
                  (login_role != 4) &
                  (login_role != 1) &
                  (login_role != 6)
                ) {
                  html +=
                    '<a class="apt-btn" href="' +
                    base_url +
                    "book-service/" +
                    this.id +
                    '">' +
                    "Book Now" +
                    "</a>";
                }
                html += "</div>" + "</div>" + "</div>" + "</div>" + "</div>";
              });

              if (obj.current_page_no == 1) {
                $("#service-list").html(html);
              } else {
                $("#service-list").append(html);
              }
            } else {
              if (role != 6) {
                var html =
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  "<p>" +
                  lg_no_doctors_foun +
                  "</p>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              } else {
                var html =
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  "<p>" +
                  lg_no_clinic_found +
                  "</p>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              }
              $("#service-list").html(html);
            }

            var minimized_elements = $("h4.minimize");
            minimized_elements.each(function () {
              var t = $(this).text();
              if (t.length < 100) return;
              $(this).html(
                t.slice(0, 100) +
                  '<span>... </span><a href="#" class="more">' +
                  lg_more +
                  "</a>" +
                  '<span style="display:none;">' +
                  t.slice(100, t.length) +
                  ' <a href="#" class="less">' +
                  lg_less +
                  "</a></span>"
              );
            });

            $(".search-results").html(
              "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
            );
            // $(".widget-title").html(obj.count+' Matches for your search');
            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 3) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_package(1);
    });
  }
}

if (modules == "home") {
  if (pages == "doctors_searchmap") {
    search_doctor(0);

    $(document).ready(function () {
      $("#services").multiselect({
        nonSelectedText: lg_select_services,
        enableClickableOptGroups: true,
        enableCollapsibleOptGroups: true,
        enableFiltering: true,
        includeSelectAllOption: true,
        includeResetOption: true,
        onChange: function (option, checked, select) {
          var selected_vals = $("#services").val();
          var selectedValues = JSON.stringify($("#services").val());
        },
      });
    });

    var locations = [];
    function reset_doctor() {
      $("#orderby").val("");
      $("#keywords").val("");
      $("#appointment_type").val("");
      $("#gender").val("");
      $("#specialization").val("");
      $("#country").val("");
      $("#state").val("");
      $("#city").val("");
      // $('#search_doctor_form')[0].reset();
      search_doctor(0);
    }

    function search_doctor(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var specialization = $("#specialization").val();
      var order_by = $("#orderby").val();
      var page = $("#page_no_hidden").val();
      var gender = $("#gender").val();
      var role = $("#role").val();
      var appointment_type = $("#appointment_type").val();
      var city = $("#city").val();
      var state = $("#state").val();
      var country = $("#country").val();
      var keywords = $("#keywords").val();

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "home/search_doctor",
        type: "POST",
        data: {
          appointment_type: appointment_type,
          gender: gender,
          specialization: specialization,
          order_by: order_by,
          role: role,
          page: page,
          keywords: keywords,
          city: city,
          state: state,
          country: country,
        },
        beforeSend: function () {
          $("#loading").show();
        },
        complete: function () {
          $("#loading").hide();
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            var obj = $.parseJSON(response);

            if (obj.data.length >= 1) {
              var html = "";
              //var locations = [];
              $(obj.data).each(function () {
                var services = "";
                var view_more = "";

                if (this.services != null && this.services.length != 0) {
                  var service = this.services.split(",");
                  for (var i = 0; i < service.length; i++) {
                    services += "<span>" + service[i] + "</span>";

                    if (i == 2) {
                      view_more =
                        '<a href="' +
                        base_url +
                        "doctor-preview/" +
                        this.username +
                        '">' +
                        lg_view_more +
                        "</a>";
                      break;
                    }
                  }
                }

                var clinic_images = "";

                var clinic_images_file = $.parseJSON(this.clinic_images);
                $.each(clinic_images_file, function (key, item) {
                  var userid = item.user_id;
                  clinic_images +=
                    '<li> <a href="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" data-fancybox="gallery"> <img src="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" alt="Feature"> </a> </li>';
                });

                html +=
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  '<div class="doc-info-left">' +
                  '<div class="doctor-img">' +
                  '<a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  '<img src="' +
                  this.profileimage +
                  '" class="img-fluid" alt="User Image">' +
                  "</a>" +
                  "</div>" +
                  '<div class="doc-info-cont">' +
                  '<h4 class="doc-name"><a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  lg_dr +
                  " " +
                  this.first_name +
                  " " +
                  this.last_name +
                  "</a></h4>" +
                  '<h5 class="doc-department">' +
                  this.speciality +
                  "</h5>" +
                  '<div class="rating">';
                for (var j = 1; j <= 5; j++) {
                  if (j <= this.rating_value) {
                    html += '<i class="fas fa-star filled"></i>';
                  } else {
                    html += '<i class="fas fa-star"></i>';
                  }
                }
                html +=
                  '<span class="d-inline-block average-rating">(' +
                  this.rating_count +
                  ")</span>" +
                  "</div>" +
                  '<div class="clinic-details">' +
                  '<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</p>" +
                  ' <ul class="clinic-gallery">' +
                  clinic_images +
                  "</ul>" +
                  "</div>" +
                  '<div class="Tags">' +
                  '<div class="clinic-services">' +
                  services +
                  "</div>" +
                  "</div>" +
                  view_more +
                  "</div>" +
                  "</div>" +
                  '<div class="doc-info-right">' +
                  '<div class="clini-infos">' +
                  "<ul>" +
                  '<li><i class="far fa-comment"></i>' +
                  this.rating_count +
                  " " +
                  lg_feedback +
                  "</li>" +
                  '<li><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</li>" +
                  '<li><i class="far fa-money-bill-alt"></i> ' +
                  this.amount +
                  " </li>";

                html +=
                  "</ul>" +
                  "</div>" +
                  '<div class="clinic-booking">' +
                  '<a class="view-pro-btn" href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  lg_view_profile +
                  "</a>" +
                  '<a class="apt-btn" href="' +
                  base_url +
                  "book-appoinments/" +
                  this.username +
                  '">' +
                  lg_book_appointmen +
                  "</a>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>";

                location_items = {};
                location_items["id"] = this.id;
                location_items["doc_name"] =
                  lg_dr + " " + this.first_name + " " + this.last_name;
                location_items["speciality"] = services;
                location_items["address"] =
                  this.cityname + ", " + this.countryname;
                location_items["next_available"] = "Available on Fri, 22 Mar";
                location_items["amount"] = this.amount;
                location_items["lat"] = this.latitude;
                location_items["lng"] = this.longitude;
                location_items["icons"] = "default";
                location_items["profile_link"] =
                  base_url + "doctor-preview/" + this.username;
                location_items["total_review"] =
                  this.rating_count + " " + lg_feedback;
                location_items["image"] = this.profileimage;

                locations.push(location_items);
              });

              initialize();

              if (obj.current_page_no == 1) {
                $("#doctor-list").html(html);
              } else {
                $("#doctor-list").append(html);
              }
            } else {
              location_items = {};
              locations.push(location_items);
              initialize();
              var html =
                '<div class="card">' +
                '<div class="card-body">' +
                '<div class="doctor-widget">' +
                "<p>" +
                lg_no_doctors_foun +
                "</p>" +
                "</div>" +
                "</div>" +
                "</div>";

              $("#doctor-list").html(html);
            }

            var minimized_elements = $("h4.minimize");
            minimized_elements.each(function () {
              var t = $(this).text();
              if (t.length < 100) return;
              $(this).html(
                t.slice(0, 100) +
                  '<span>... </span><a href="#" class="more">' +
                  lg_more +
                  "</a>" +
                  '<span style="display:none;">' +
                  t.slice(100, t.length) +
                  ' <a href="#" class="less">' +
                  lg_less +
                  "</a></span>"
              );
            });

            $(".search-results").html(
              "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
            );
            // $(".widget-title").html(obj.count+' Matches for your search');
            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 5) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_doctor(1);
    });
  }
}

if (modules == "home") {
  if (pages == "patients_search") {
    function reset_patient() {
      $("#orderby").val("");
      $("#search_patient_form")[0].reset();
      search_patient(0);
    }

    search_patient(0);
    function search_patient(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var order_by = $("#orderby").val();
      var keyword = $("#search_user").val();
      var page = $("#page_no_hidden").val();
      var gender = $("#gender").val();
      var blood_group = $("#blood_group").val();
      var city = $("#city").val();
      var state = $("#state").val();
      var country = $("#country").val();

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "home/search_patient",
        type: "POST",
        data: {
          gender: gender,
          blood_group: blood_group,
          order_by: order_by,
          page: page,
          keyword: keyword,
          city: city,
          state: state,
          country: country,
        },
        beforeSend: function () {
          // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            var obj = $.parseJSON(response);
            if (obj.data.length >= 1) {
              var html = "";
              $(obj.data).each(function () {
                html +=
                  '<div class="col-md-6 col-lg-4 col-xl-3">' +
                  '<div class="card widget-profile pat-widget-profile">' +
                  '<div class="card-body">' +
                  '<div class="pro-widget-content">' +
                  '<div class="profile-info-widget">' +
                  '<a href="javascript:void(0)" class="booking-doc-img">' +
                  '<img src="' +
                  this.profileimage +
                  '" alt="User Image">' +
                  "</a>" +
                  '<div class="profile-det-info">' +
                  '<h3><a href="javascript:void(0)">' +
                  this.first_name +
                  " " +
                  this.last_name +
                  "</a></h3>" +
                  '<div class="patient-details">' +
                  "<h5><b>" +
                  lg_patient_id +
                  " :</b> #PT00" +
                  this.user_id +
                  "</h5>" +
                  '<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</h5>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  '<div class="patient-info">' +
                  "<ul>" +
                  "<li>" +
                  lg_phone +
                  " <span>" +
                  this.mobileno +
                  "</span></li>" +
                  "<li>" +
                  lg_age +
                  " <span>" +
                  this.age +
                  ", " +
                  this.gender +
                  "</span></li>" +
                  "<li>" +
                  lg_blood_group +
                  " <span>" +
                  this.blood_group +
                  "</span></li>" +
                  "</ul>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              });

              if (obj.current_page_no == 1) {
                $("#patients-list").html(html);
              } else {
                $("#patients-list").append(html);
              }
            } else {
              var html =
                '<div class="card" style="width:100%">' +
                '<div class="card-body">' +
                '<div class="doctor-widget">' +
                "<p>" +
                lg_no_patients_fou +
                "</p>" +
                "</div>" +
                "</div>" +
                "</div>";

              $("#patients-list").html(html);
            }

            var minimized_elements = $("h4.minimize");
            minimized_elements.each(function () {
              var t = $(this).text();
              if (t.length < 100) return;
              $(this).html(
                t.slice(0, 100) +
                  '<span>... </span><a href="#" class="more">' +
                  lg_more +
                  "</a>" +
                  '<span style="display:none;">' +
                  t.slice(100, t.length) +
                  ' <a href="#" class="less">' +
                  lg_less +
                  "</a></span>"
              );
            });

            $(".search-results").html(
              "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
            );
            // $(".widget-title").html(obj.count+' Matches for your search');
            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 2) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_patient(1);
    });
  }
}

if (modules == "doctor") {
  if (pages == "schedule_timings") {
    $(document).ready(function () {
      $(document).on("click", ".timingsnav li a", function () {
        var day_id = $(this).attr("data-day-value");
        var append_html = $(this).attr("data-append-value");
        var day_name = $(this).text();

        $("#slot_" + append_html).html(
          '<div class="d-flex justify-content-center"><div class="spinner-grow text-success" style="width: 3rem; height: 3rem;" role="status"></div></div>'
        );
        get_time_slot(1);
        setTimeout(function () {
          $.post(
            base_url + "schedule_timings/schedule_list",
            { day_id: day_id, day_name: day_name, append_html: append_html },
            function (result) {
              $("#slot_" + append_html).html(result);
              //$('.overlay').hide();
            }
          );
        }, 500);
      });

      $(document).on("click", ".delete_schedule", function () {
        var delete_value = $(this).attr("data-delete-val");
        var append_html = $("#id_value").val();
        var c = confirm(lg_are_you_sure_to);
        if (c) {
          $.post(
            base_url + "schedule_timings/delete_schedule_time",
            { delete_value: delete_value },
            function (res) {
              if (res == 1) {
                $("#" + append_html).click();
              }
            }
          );
        }
      });

      $("#slots").change(function () {
        //toastr.warning(lg_your_existing_s);
        $("#sunday").click();
      });

      $("#sunday").click();
    });

    const nth = function (d) {
      if (d > 3 && d < 21) return "th";
      switch (d % 10) {
        case 1:
          return "st";
        case 2:
          return "nd";
        case 3:
          return "rd";
        default:
          return "th";
      }
    };

    function add_hours() {
      var i = $("#slot_count").val();
      i = Number(i) + 1;
      $("#slot_count").val(i);
      var j = Number(i) + 1;
      var k = Number(i) - 1;

      var hourscontent =
        '<div class="row form-row hours-cont" id="hours-cont_' +
        i +
        '">' +
        '<div class="col-12 col-md-11">' +
        '<h4 class="h4 text-center breadcrumb-bar px-2 py-1 mx-3 rounded text-white">' +
        i +
        "<sup>" +
        nth(i) +
        "</sup> " +
        lg_session +
        " </h4>" +
        '<input type="hidden" name="sessions[]" value="' +
        i +
        '">' +
        '<div class="row form-row">' +
        '<div class="col-12 col-md-4">' +
        '<div class="form-group">' +
        "<label>" +
        lg_start_time +
        "</label>" +
        '<select class="form-control start_time" name="start_time[' +
        i +
        ']" onchange="get_end_time(' +
        i +
        ')" id="start_time_' +
        i +
        '">' +
        '<option value="">Select</option>' +
        "</select>" +
        "</div>" +
        "</div>" +
        '<div class="col-12 col-md-4">' +
        '<div class="form-group">' +
        "<label>" +
        lg_end_time +
        "</label>" +
        '<select class="form-control end_time" name="end_time[' +
        i +
        ']" onchange="get_time_slot(' +
        j +
        "),get_tokens(" +
        i +
        ')" id="end_time_' +
        i +
        '">' +
        '<option value="">Select</option>' +
        "</select>" +
        "</div>" +
        "</div>" +
        '<div class="col-12 col-md-4">' +
        '<div class="form-group">' +
        "<label>" +
        lg_no_of_tokens +
        "</label>" +
        '<input type="text" class="form-control" id="token_' +
        i +
        '" name="token[' +
        i +
        ']" readonly="">' +
        "</div>" +
        "</div>" +
        "</div>" +
        "</div>" +
        '<div id="remove_btn_' +
        i +
        '" class="col-12 col-md-1 slot-drash-btn"><label class="d-md-block d-sm-none d-none">&nbsp;</label><a href="javascript:void(0)" onclick="remove_session(' +
        i +
        ')" class="btn btn-danger trash"><i class="far fa-trash-alt"></i></a></div>' +
        "</div>";

      $("#remove_btn_" + k).hide();
      $(".hours-info").append(hourscontent);
      get_time_slot(i);

      return false;
    }

    function remove_session(id) {
      var k = Number(id) - 1;
      $("#slot_count").val(k);
      $("#hours-cont_" + id).remove();
      toastr.error("Session deleted!");
      $("#remove_btn_" + k).show();
    }

    function get_tokens(id) {
      var slots = $("#slots").val();
      var start_time = $("#start_time_" + id).val();
      var end_time = $("#end_time_" + id).val();
      $.post(
        base_url + "schedule_timings/get_tokens",
        { slots: slots, start_time: start_time, end_time: end_time },
        function (data) {
          $("#token_" + id).val(data);
        }
      );
    }

    function get_time_slot(id) {
      var slot = $("#slots").val();
      var sessions = $("#sessions_" + id).val();
      if (id == 1) {
        var end_time = "";
      } else {
        var a = Number(id) - 1;
        var end_time = $("#end_time_" + a).val();
      }
      if (slot != "") {
        $.ajax({
          type: "POST",
          url: base_url + "schedule_timings/get_available_time_slots",
          data: {
            slot: $("#slots").val(),
            day_id: $("#day_id").val(),
            end_time: end_time,
            sessions: sessions,
          },
          beforeSend: function () {
            $("#start_time_" + id + " option:gt(0)").remove();
            $("#end_time_" + id + " option:gt(0)").remove();
            $("#start_time_" + id + ",#end_time_" + id + "")
              .find("option:eq(0)")
              .html(lg_please_wait);
          },
          success: function (data) {
            $("#start_time_" + id + ",#end_time_" + id + "")
              .find("option:eq(0)")
              .html(lg_select_time);
            var obj = jQuery.parseJSON(data);
            $(obj).each(function () {
              var option = $("<option />");
              if (this.added == true) {
                option.attr("value", this.value).text(this.label);
                option.attr("disabled", true);
                option.addClass("d-none");
              } else {
                option.attr("value", this.value).text(this.label);
              }
              $("#start_time_" + id + ",#end_time_" + id + "").append(option);
            });
          },
        });
      } else {
        $("#start_time_" + id + " option:gt(0)").remove();
        $("#end_time_" + id + " option:gt(0)").remove();
      }
    }

    function get_end_time(id) {
      var slot = $("#slots").val();
      var start_time = $("#start_time_" + id).val();
      var sessions = $("#sessions_" + id).val();

      if (slot != "" && start_time != "") {
        $.ajax({
          type: "POST",
          url: base_url + "schedule_timings/get_available_time_slots",
          data: {
            slot: slot,
            day_id: $("#day_id").val(),
            start_time: start_time,
          },
          beforeSend: function () {
            //$('.overlay').show();
            $("#end_time_" + id + " option:gt(0)").remove();
            $("#end_time_" + id)
              .find("option:eq(0)")
              .html(lg_please_wait);
          },
          success: function (data) {
            // $('.overlay').hide();
            $("#end_time_" + id)
              .find("option:eq(0)")
              .html(lg_select_time);
            var obj = jQuery.parseJSON(data);
            $(obj).each(function () {
              var option = $("<option />");
              if (this.added == true) {
                option.attr("value", this.value).text(this.label);
                option.attr("disabled", true);
                option.addClass("d-none");
              } else {
                option.attr("value", this.value).text(this.label);
              }
              $("#end_time_" + id).append(option);
            });
          },
        });
      }
    }

    function add_slot(day_id, day_name, append_html) {
      var slot = $("#slots").val();
      if (slot == "") {
        toastr.error(lg_please_select_s2);
      } else {
        $.post(
          base_url + "schedule_timings/get_slots",
          {
            day_id: day_id,
            append_html: append_html,
            day_name: day_name,
            slot: slot,
          },
          function (response) {
            $(".slotdetails").html(response);
            get_time_slot(1);
            $("#time_slot_modal").modal("show");

            $("#schedule_form").on("submit", function (event) {
              //Add validation rule for dynamically generated name fields
              $(".start_time").each(function () {
                $(this).rules("add", {
                  required: true,
                  messages: {
                    required: lg_start_time_is_r,
                  },
                });
              });
              //Add validation rule for dynamically generated email fields
              $(".end_time").each(function () {
                $(this).rules("add", {
                  required: true,
                  messages: {
                    required: lg_end_time_is_req,
                  },
                });
              });

              event.preventDefault();

              if ($("#schedule_form").validate().form()) {
                var day_id = $('[name="day_id[]"]:checked').val();

                if (!day_id) {
                  toastr.error(lg_please_choose_a);
                  return false;
                }

                $.ajax({
                  url: base_url + "schedule_timings/add_schedule",
                  data: $("#schedule_form").serialize(),
                  type: "POST",
                  beforeSend: function () {
                    $("#submit_btn").attr("disabled", true);
                    $("#submit_btn").html(
                      '<div class="spinner-border text-light" role="status"></div>'
                    );
                  },
                  success: function (res) {
                    $("#submit_btn").attr("disabled", false);
                    $("#submit_btn").html(lg_add10);

                    var obj = JSON.parse(res);

                    if (obj.status === 200) {
                      var append_html = $("#id_value").val();
                      $("#" + append_html).click();
                      $("#time_slot_modal").modal("hide");
                      $("#schedule_form")[0].reset();
                      toastr.success(obj.msg);
                    } else {
                      toastr.error(obj.msg);
                    }
                  },
                });
                return false;
              }
            });
            $("#schedule_form").validate();
          }
        );
      }
    }

    function edit_slot(day_id) {
      $.post(
        base_url + "schedule_timings/get_day_slots",
        { day_id: day_id },
        function (response) {
          var obj = JSON.parse(response);
          $(".slotdetails").html(obj.details);

          $("#time_slot_modal").modal("show");
          $("#edit_schedule_form").on("submit", function (event) {
            //Add validation rule for dynamically generated name fields
            $(".start_time").each(function () {
              $(this).rules("add", {
                required: true,
                messages: {
                  required: lg_start_time_is_r,
                },
              });
            });
            //Add validation rule for dynamically generated email fields
            $(".end_time").each(function () {
              $(this).rules("add", {
                required: true,
                messages: {
                  required: lg_end_time_is_req,
                },
              });
            });

            event.preventDefault();

            if ($("#edit_schedule_form").validate().form()) {
              $.ajax({
                url: base_url + "schedule_timings/update_schedule",
                data: $("#edit_schedule_form").serialize(),
                type: "POST",
                beforeSend: function () {
                  $("#submit_btn").attr("disabled", true);
                  $("#submit_btn").html(
                    '<div class="spinner-border text-light" role="status"></div>'
                  );
                },
                success: function (res) {
                  $("#submit_btn").attr("disabled", false);
                  $("#submit_btn").html(lg_add10);

                  var obj = JSON.parse(res);

                  if (obj.status === 200) {
                    var append_html = $("#id_value").val();
                    $("#" + append_html).click();
                    $("#time_slot_modal").modal("hide");
                    toastr.success(obj.msg);
                  } else {
                    toastr.error(obj.msg);
                  }
                },
              });
              return false;
            }
          });
          $("#edit_schedule_form").validate();
        }
      );
    }
  }
}

if (modules == "patient") {
  if (pages == "book_appoinments") {
    function getSchedule() {
      var date = $("#schedule_date").val();
      var schedule_date = date.split("/").reverse().join("-");
      if (schedule_date == "") {
        $("#schedule_date_error").html(
          '<small class="help-block" data-bv-validator="notEmpty" data-bv-for="schedule_date" data-bv-result="INVALID" style="color:red;">' +
            lg_date_is_require +
            "</small>"
        );
        return false;
      }

      $("#schedule_date_error").html("");
      var doctor_id = $("#doctor_id").val();
      $.post(
        base_url + "book_appoinments/get_schedule_from_date",
        { schedule_date: schedule_date, doctor_id: doctor_id },
        function (response) {
          $(".bookings-schedule").html(response);
          $('[data-toggle="tooltip"]').tooltip();
        }
      );
    }

    getSchedule();

    $(document).ready(function () {
      $("#schedule_date").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        todayHighlight: true,
        startDate: "d",
      });
    });

    function checkout() {
      var doctor_id = $("#doctor_id").val();
      var hourly_rate = $("#hourly_rate").val();
      var price_type = $("#price_type").val();
      var role_id = $("#role_id").val();
      //var type = $("input[name='type']:checked"). val();
      var type;

      if (role_id == 6) {
        type = "Clinic";
      } else {
        type = "Online";
      }

      var appoinment_token = $("input[name='token']:checked").val();
      var appoinment_date = $("input[name='slots']:checked").attr("data-date");
      var appoinment_timezone = $("input[name='slots']:checked").attr(
        "data-timezone"
      );
      var appoinment_time = $("input[name='slots']:checked").attr("data-time");
      var appoinment_session = $("input[name='slots']:checked").attr(
        "data-session"
      );
      if (!appoinment_token) {
        toastr.warning(lg_please_select_a1);
        return false;
      }
      var appointment_data = [];
      appointment_data.push({
        appoinment_token: $("input[name='token']:checked").val(),
        appoinment_date: $("input[name='token']:checked").attr("data-date"),
        appoinment_timezone: $("input[name='token']:checked").attr(
          "data-timezone"
        ),
        appoinment_start_time: $("input[name='token']:checked").attr(
          "data-start-time"
        ),
        appoinment_end_time: $("input[name='token']:checked").attr(
          "data-end-time"
        ),
        appoinment_session: $("input[name='token']:checked").attr(
          "data-session"
        ),
        type: type,
      });
      var appointment_details = JSON.stringify(appointment_data);

      $("#pay_btn").attr("disabled", true);
      $("#pay_btn").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      $.post(
        base_url + "book_appoinments/set_booked_session",
        {
          hourly_rate: hourly_rate,
          appointment_details: appointment_details,
          price_type: $("#price_type").val(),
          doctor_id: doctor_id,
          doctor_role_id: role_id,
        },
        function (res) {
          var obj = JSON.parse(res);
          if (obj.status === 200) {
            setTimeout(function () {
              window.location = base_url + "checkout";
            }, 1000);
          } else if (obj.status === 500) {
            toastr.error(obj.message);
            $("#pay_btn").attr("disabled", false);
            $("#pay_btn").html(lg_proceed_to_pay);
          } else {
            toastr.success(lg_transaction_suc);
            setTimeout(function () {
              window.location.href = base_url + "dashboard";
            }, 2000);
          }
        }
      );
    }
  }
}

if (modules == "ecommerce" && pages == "checkout") {
  console.log(pages);
  function login_cart() {
    var login_email = $("#login_email").val();
    var login_password = $("#login_password").val();
    if (login_email == "") {
      toastr.error(lg_please_enter_em1);
      return false;
    }
    if (login_password == "") {
      toastr.error(lg_please_enter_pa);
      return false;
    }

    $.ajax({
      url: base_url + "signin/is_valid_login",
      data: {
        email: login_email,
        password: login_password,
      },
      type: "POST",
      beforeSend: function () {
        $("#cart_login_btn").attr("disabled", true);
        $("#cart_login_btn").html(
          '<div class="spinner-border text-light" role="status"></div>'
        );
      },
      success: function (res) {
        $("#cart_login_btn").attr("disabled", false);
        $("#cart_login_btn").html(lg_signin);

        var obj = JSON.parse(res);

        if (obj.status === 200) {
          window.location.href = base_url + "cart-checkout";
        } else {
          toastr.error(obj.msg);
        }
      },
    });
    return false;
  }

  $(document).ready(function () {
    $("#shipping_form").validate({
      rules: {
        ship_name: "required",
        ship_mobile: {
          required: true,
          minlength: 7,
          maxlength: 12,
          digits: true,
        },
        ship_email: {
          required: true,
          email: true,
        },
        ship_address_1: "required",
        ship_country: "required",
        ship_state: "required",
        ship_city: "required",
        postal_code: {
          required: true,
          minlength: 4,
          maxlength: 7,
          digits: true,
        },
      },
      messages: {
        ship_name: lg_please_enter_yo5,
        mobileno: {
          required: lg_please_enter_mo,
          maxlength: lg_please_enter_mo,
          minlength: lg_please_enter_mo,
          digits: lg_please_enter_mo,
        },
        email: {
          required: lg_please_enter_em,
          email: lg_please_enter_va1,
        },

        ship_address_1: lg_please_enter_yo6,
        ship_country: lg_please_select_c,
        ship_state: lg_please_select_s,
        ship_city: lg_please_select_c1,

        postal_code: {
          required: lg_please_enter_po,
          maxlength: lg_please_enter_va2,
          minlength: lg_please_enter_va2,
          digits: lg_please_enter_va2,
        },
      },
      submitHandler: function (form) {
        $.ajax({
          url: base_url + "cart/add_shipping_details",
          data: $("#shipping_form").serialize(),
          type: "POST",
          beforeSend: function () {},
          success: function (res) {},
        });
        return false;
      },
    });
  });

  $(document).ready(function () {
    $("input[type=radio][name=payment_methods]").change(function () {
      payment_methods = $(
        "input[type=radio][name=payment_methods]:checked"
      ).val();
      if (payment_methods == "PayPal") {
        $(".stripe_payment").hide();
        $(".paypal_payment").show();
        $(".cybersource_payment").hide();
        $("#payment_method").val("Card Payment");
      } else if (payment_methods == "Cybersource") {
        $(".stripe_payment").hide();
        $(".paypal_payment").hide();
        $(".cybersource_payment").show();
        $("#payment_method").val("Card Payment");
      } else {
        $("#payment-form").trigger("submit");
      }
    });
  });

  /*submit form ajax template*/ /*
    $("#payment-form").submit(function (e) {
       // e.preventDefault();
    }).validate({
        rules: {
            ship_name: {
                required: true,
                minlength: 3,
                maxlength: 150,
                text_spaces_only: true
            },
            ship_email: {
                required: true,
                email: true
            },
            ship_mobile: {
                required: true,
                minlength: 7,
                maxlength: 12,
                digits: true,
            },
            ship_country: {
                required: true
            },
            ship_address_1: {
                required: true,
                address_validation: true,
                maxlength: 500
            },
            ship_address_2: {
                address_validation: true,
                maxlength: 500
            },
            ship_state: {
                required: true
            },
            ship_city: {
                required: true
            },
            postal_code: {
                required: true,
                minlength: 4,
                maxlength: 7,
                digits: true,
            },

        },
        messages: {
            ship_name: {
                required: lg_pers_info_name_req,
                minlength: lg_pers_info_name_min,
                maxlength: lg_pers_info_name_max,
            },
            ship_email: {
                required: lg_pers_info_email_req,
                email: lg_pers_info_email_val,
            },
            ship_mobile: {
                required: lg_pers_info_mobile_req,
                minlength: lg_pers_info_mobile_min,
                maxlength: lg_pers_info_mobile_max,
                digits: lg_pers_info_mobile_val,
            },
            ship_country: {
                required: lg_pers_info_country_req,
            },
            ship_address_1: {
                required: lg_pers_info_address_req,
                address_validation: lg_pers_info_address_val,
                maxlength: lg_enter_address_max,
            },
            ship_address_2: {
                address_validation: lg_pers_info_address_val,
                maxlength: lg_enter_address_max,
            },
            ship_state: {
                required: lg_pers_info_state_req,
            },
            ship_city: {
                required: lg_pers_info_city_req,
            },
            postal_code: {
                required: lg_pers_info_postalcode_req,
                minlength: lg_pers_info_postalcode_min,
                maxlength: lg_pers_info_postalcode_max,
                digits: lg_pers_info_postalcode_val,
            },

        },
        invalidHandler: function (event, validator) {
            payment_methods = $('input[type=radio][name=payment_methods]:checked').val();
            if (payment_methods == 'PayPal') {
                $('#pay_buttons').attr('disabled', false);
                $('#pay_buttons').html(lg_confirm_and_pay);
            } else {
                $('input[type=radio][name=payment_methods]:checked').prop('checked', false);
            }
        },
        submitHandler: function (form) {
            payment_methods = $('input[type=radio][name=payment_methods]:checked').val();

           /* if (payment_methods == 'Card Payment') {
                $('.stripe_payment').show();
                $('.paypal_payment').hide();
                $('.cybersource_payment').hide();
                $('#payment_method').val('Card Payment');

            } else if (payment_methods == 'Cybersource') {
                $('.stripe_payment').hide();
                $('.paypal_payment').hide();
                $('.cybersource_payment').show();
                $('#payment_method').val('Card Payment');

            } else if (payment_methods == 'PayPal') {
                $('.stripe_payment').hide();
                $('.paypal_payment').show();
                $('.cybersource_payment').hide();
                $('#payment_method').val('Card Payment');
                //form.submit();

            } else {
                $('.stripe_payment').hide();
                $('.paypal_payment').hide();
                $('#payment_method').val('');
            }*/ /*
            return false;
        }
    });*/
  /*submit form ajax template*/

  function pharmacy_payment(type) {
    // var terms_accept=$("input[name='terms_accept']:checked").val();
    var terms_accept = 1;
    if (terms_accept == "1") {
      if (type == "paypal") {
        $("#pay_buttons").attr("disabled", true);
        $("#pay_buttons").html(
          '<div class="spinner-border text-light" role="status"></div>'
        );
        $("#payment-form").attr("action", base_url + "cart/paypal_pay");
        $("#payment-form").submit();
      } else if (type == "Cybersource") {
        $("#payment_confirmation").submit();
        //return false;
      } else {
        var payment_method = $("input[name='payment_methods']:checked").val();
        if (payment_method != "Card Payment") {
          $("#my_book_appoinment").click();
        }

        return false;
      }
    } else {
      toastr.warning(lg_please_accept_t);
    }
  }

  /*var stripe = Stripe(stripe_api_key);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
        base: {
            color: '#32325d',
            fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
            fontSmoothing: 'antialiased',
            fontSize: '16px',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#fa755a',
            iconColor: '#fa755a'
        }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', { style: style });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
        } else {
            displayError.textContent = '';
        }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        $('#stripe_pay_btn').attr('disabled', true);
        $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(stripePaymentMethodHandler);
    });*/
  /*
    function stripePaymentMethodHandler(result) {
        if (result.error) {
            $('#card-errors').text(result.error.message);
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
            // Show error in payment form
        } else {
            // Otherwise send paymentMethod.id to your server 

            var payment_method = $('#myself:checked').val();

            ship_name = $('#ship_name').val();
            ship_email = $('#ship_email').val();
            ship_mobile = $('#ship_mobile').val();
            ship_address_1 = $('#ship_address_1').val();
            ship_address_2 = $('#ship_address_2').val();
            country = $('#country').val();
            state = $('#state').val();
            city = $('#city').val();
            postal_code = $('#postal_code').val();
            shipping = $('#shipping').val();
            total_amount = $('#total_amount').val();
            currency_code = $('#currency_code').val();

            if (ship_name == '') {
                toastr.error(lg_name_is_require);
                return false;
            }

            if (ship_email == '') {
                toastr.error(lg_email_is_requir);
                return false;
            }

            if (ship_mobile == '') {
                toastr.error(lg_mobile_no_is_re);
                return false;
            }

            if (ship_address_1 == '') {
                toastr.error(lg_address1_is_req);
                return false;
            }

            if (country == '') {
                toastr.error(lg_country_is_requ);
                return false;
            }

            if (state == '') {
                toastr.error(lg_state_is_requir);
                return false;
            }

            if (city == '') {
                toastr.error(lg_city_is_require);
                return false;
            }

            if (postal_code == '') {
                toastr.error(lg_postal_code_is_);
                return false;
            }

            $.ajax({
                url: base_url + 'cart/stripe_payment',
                data: { payment_method_id: result.paymentMethod.id, payment_method: payment_method, ship_name: ship_name, ship_email: ship_email, ship_mobile: ship_mobile, ship_address_1: ship_address_1, ship_address_2: ship_address_2, country: country, state: state, city: city, postal_code: postal_code, shipping: shipping, total_amount: total_amount, currency_code: currency_code },
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#stripe_pay_btn').attr('disabled', true);
                    $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {

                    handleServerResponse(response);

                },
                error: function (error) {
                    console.log(error);
                }
            });

        }
    } */
  /*
    function handleServerResponse(response) {
        if (response.status == '500') {
            toastr.error(response.message);
            $('#stripe_pay_btn').attr('disabled', false);
            $('#stripe_pay_btn').html('Confirm and Pay');
        } else if (response.status == '201') {
            // Use Stripe.js to handle required card action
            stripe.handleCardAction(
                response.payment_intent_client_secret
            ).then(handleStripeJsResult);
        } else {
            if (response.status == '200') {

                toastr.success(lg_your_order_has_);
                setTimeout(function () {
                    window.location.href = base_url + 'payment-sucess';
                }, 2000);

            } else {
                toastr.error(lg_order_failed);
                setTimeout(function () {
                    window.location.href = base_url + 'cart-list';
                }, 2000);
            }
        }
    }

    function handleStripeJsResult(result) {

        if (result.status == '500') {
            toastr.error(result.message);
        } else {
            // The card action has been handled
            // The PaymentIntent can be confirmed again on the server

            var payment_method = $('#myself:checked').val();

            ship_name = $('#ship_name').val();
            ship_email = $('#ship_email').val();
            ship_mobile = $('#ship_mobile').val();
            ship_address_1 = $('#ship_address_1').val();
            ship_address_2 = $('#ship_address_2').val();
            country = $('#country').val();
            state = $('#state').val();
            city = $('#city').val();
            postal_code = $('#postal_code').val();
            shipping = $('#shipping').val();
            total_amount = $('#total_amount').val();
            currency_code = $('#currency_code').val();

            if (ship_name == '') {
                toastr.error(lg_name_is_require);
                return false;
            }

            if (ship_email == '') {
                toastr.error(lg_email_is_requir);
                return false;
            }

            if (ship_mobile == '') {
                toastr.error(lg_mobile_no_is_re);
                return false;
            }

            if (ship_address_1 == '') {
                toastr.error(lg_address1_is_req);
                return false;
            }

            if (country == '') {
                toastr.error(lg_country_is_requ);
                return false;
            }

            if (state == '') {
                toastr.error(lg_state_is_requir);
                return false;
            }

            if (city == '') {
                toastr.error(lg_city_is_require);
                return false;
            }

            if (postal_code == '') {
                toastr.error(lg_postal_code_is_);
                return false;
            }

            $.ajax({
                url: base_url + 'cart/stripe_payment',
                data: { payment_intent_id: result.paymentIntent.id, payment_method: payment_method, ship_name: ship_name, ship_email: ship_email, ship_mobile: ship_mobile, ship_address_1: ship_address_1, ship_address_2: ship_address_2, country: country, state: state, city: city, postal_code: postal_code, shipping: shipping, total_amount: total_amount, currency_code: currency_code },
                type: 'POST',
                dataType: 'JSON',
                beforeSend: function () {
                    $('#stripe_pay_btn').attr('disabled', true);
                    $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
                },
                success: function (response) {

                    handleServerResponse(response);

                },
                error: function (error) {
                    console.log(error);
                }
            });

        }
    }
    */
}

if (modules == "patient") {
  if (pages == "checkout") {
    $(".OTP").hide();
    $("#resendotp").hide();

    function register() {
      $("#login_modal").modal("hide");
      $("#register_modal").modal("show");
    }

    function login() {
      $("#register_modal").modal("hide");
      $("#forgot_password_modal").modal("hide");
      $("#login_modal").modal("show");
    }

    function forgot_password() {
      $("#login_modal").modal("hide");
      $("#forgot_password_modal").modal("show");
    }

    $("#reset_password").validate({
      rules: {
        resetemail: {
          required: true,
          email: true,
          remote: {
            url: base_url + "signin/check_resetemail",
            type: "post",
            data: {
              resetemail: function () {
                return $("#resetemail").val();
              },
            },
          },
        },
      },
      messages: {
        resetemail: {
          required: lg_please_enter_em,
          email: lg_please_enter_va1,
          remote: lg_your_email_addr,
        },
      },
      submitHandler: function (form) {
        $.ajax({
          url: base_url + "signin/reset_password",
          data: $("#reset_password").serialize(),
          type: "POST",
          beforeSend: function () {
            $("#reset_pwd").attr("disabled", true);
            $("#reset_pwd").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (res) {
            $("#reset_pwd").attr("disabled", false);
            $("#reset_pwd").html(lg_reset_password);

            var obj = JSON.parse(res);

            if (obj.status === 200) {
              $("#reset_password")[0].reset();
              toastr.success(obj.msg);
              $("#forgot_password_modal").modal("hide");
              $("#login_modal").modal("show");
            } else {
              toastr.error(obj.msg);
            }
          },
        });
        return false;
      },
    });

    $("#sendotp").on("click", function () {
      var mobileno = $("#mobileno").val();
      var country_code = $("#country_code").val();
      if (mobileno == "") {
        toastr.error(lg_please_enter_va4);
      } else {
        $.ajax({
          url: base_url + "Signin/sendotp",
          data: {
            mobileno: mobileno,
            country_code: country_code,
            otpcount: "1",
          },

          dataType: "text",
          method: "post",
          beforeSend: function () {
            $(".otp_load").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (res) {
            $(".otp_load").html(
              '<a class="forgot-link" onclick="resend_otp()"  href="javascript:void(0);" id="resendotp">' +
                lg_resend_otp +
                "</a>"
            );

            var obj = JSON.parse(res);

            if (obj.status === 200) {
              $(".OTP").show();
              $("#resendotp").show();
              toastr.success(obj.msg);
            } else if (obj.status === 500) {
              toastr.error(obj.msg);
            } else {
              toastr.error(obj.msg);
            }
          },
        });
      }
    });

    $("#register_form").validate({
      rules: {
        first_name: "required",
        last_name: "required",
        mobileno: {
          required: true,
          minlength: 7,
          maxlength: 12,
          digits: true,
          remote: {
            url: base_url + "signin/check_mobileno",
            type: "post",
            data: {
              mobileno: function () {
                return $("#mobileno").val();
              },
            },
          },
        },
        email: {
          required: true,
          email: true,
          remote: {
            url: base_url + "signin/check_email",
            type: "post",
            data: {
              email: function () {
                return $("#register_email").val();
              },
            },
          },
        },
        password: {
          required: true,
          minlength: 6,
        },
        confirm_password: {
          required: true,
          equalTo: "#register_password",
        },
      },
      messages: {
        first_name: lg_please_enter_yo,
        last_name: lg_please_enter_yo1,
        mobileno: {
          required: lg_please_enter_mo,
          maxlength: lg_please_enter_va,
          minlength: lg_please_enter_va,
          digits: lg_please_enter_va,
          remote: lg_your_mobile_no_,
        },
        email: {
          required: lg_please_enter_em,
          email: lg_please_enter_va1,
          remote: lg_your_email_addr1,
        },
        password: {
          required: lg_please_enter_pa,
          minlength: lg_your_password_m,
        },
        confirm_password: {
          required: lg_please_enter_co,
          equalTo: lg_your_password_d,
        },
      },

      submitHandler: function (form) {
        $.ajax({
          url: base_url + "signin/signup",
          data: $("#register_form").serialize(),
          type: "POST",
          beforeSend: function () {
            $("#register_btn").attr("disabled", true);
            $("#register_btn").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (res) {
            $("#register_btn").attr("disabled", false);
            $("#register_btn").html(lg_signup);
            var obj = JSON.parse(res);

            if (obj.status === 200) {
              $("#register_form")[0].reset();
              toastr.success(obj.msg);
              $("#register_modal").modal("hide");
              $("#login_modal").modal("show");
            } else {
              toastr.error(obj.msg);
            }
          },
        });
        return false;
      },
    });

    $("#signin_form").validate({
      rules: {
        email: "required",
        password: {
          required: true,
          minlength: 6,
        },
      },
      messages: {
        email: lg_please_enter_em,
        password: {
          required: lg_please_enter_pa,
          minlength: lg_your_password_m,
        },
      },
      submitHandler: function (form) {
        $.ajax({
          url: base_url + "signin/is_valid_login",
          data: $("#signin_form").serialize(),
          type: "POST",
          beforeSend: function () {
            $("#signin_btn").attr("disabled", true);
            $("#signin_btn").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (res) {
            $("#signin_btn").attr("disabled", false);
            $("#signin_btn").html(lg_signin);

            var obj = JSON.parse(res);

            if (obj.status === 200) {
              $("#login_modal").modal("hide");
              toastr.success("logged-in successfully");
              window.location.reload();
            } else {
              toastr.error(obj.msg);
            }
          },
        });
        return false;
      },
    });

    function appoinment_payment(type) {
      // var terms_accept=$("input[name='terms_accept']:checked").val();
      var terms_accept = 1;
      if (terms_accept == "1") {
        if (type == "paypal") {
          $("#payment_formid").submit();
        } else if (type == "razorpay") {
          razorpay();
        } else if (type == "Cybersource") {
          $("#payment_confirmation").submit();
          return false;
        } else {
          var payment_method = $("input[name='payment_methods']:checked").val();
          if (payment_method != "Card Payment") {
            $("#my_book_appoinment").click();
          }

          return false;
        }
      } else {
        toastr.warning(lg_please_accept_t);
      }
    }

    function razorpay() {
      $("#razor_pay_btn").attr("disabled", true);
      $("#razor_pay_btn").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      var amount = $("#amount").val();
      var currency_code = $("#currency_code").val();
      $.post(
        base_url + "book_appoinments/create_razorpay_orders",
        { amount: amount, currency_code: currency_code },
        function (data) {
          $("#razor_pay_btn").attr("disabled", false);
          $("#razor_pay_btn").html(lg_confirm_and_pay2);
          var obj = jQuery.parseJSON(data);
          var options = {
            key: obj.key_id,
            amount: obj.amount,
            currency: obj.currency,
            name: obj.sitename,
            description: "Booking Slot",
            image: obj.siteimage,
            order_id: obj.order_id,
            handler: function (response) {
              razorpay_appoinments(
                response.razorpay_payment_id,
                response.razorpay_order_id,
                response.razorpay_signature
              );
            },
            prefill: {
              name: obj.patientname,
              email: obj.email,
              contact: obj.mobileno,
            },
            notes: {
              address: "Razorpay Corporate Office",
            },
            theme: {
              color: "#F37254",
            },
          };

          var rzp1 = new Razorpay(options);
          rzp1.open();
        }
      );
    }

    function razorpay_appoinments(payment_id, order_id, signature) {
      $("#payment_id").val(payment_id);
      $("#order_id").val(order_id);
      $("#signature").val(signature);

      $.ajax({
        url: base_url + "book_appoinments/razorpay_appoinments",

        data: $("#payment_formid").serialize(),

        type: "POST",

        dataType: "JSON",

        beforeSend: function () {
          $("#razor_pay_btn").attr("disabled", true);
          $("#razor_pay_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },

        success: function (response) {
          // $('.overlay').hide();
          if (response.status == "200") {
            toastr.success(lg_transaction_suc);
            setTimeout(function () {
              window.location.href =
                base_url +
                "book_appoinments/payment_sucess/" +
                response.appointment_id;
            }, 2000);
            // window.location.href = base_url + 'book_appoinments/payment_sucess/'+response.appointment_id;
          } else {
            toastr.error(lg_transaction_fai1);
            setTimeout(function () {
              window.location.href = base_url + "dashboard";
            }, 2000);
          }
        },

        error: function (error) {
          console.log(error);
        },
      });
    }

    var stripe = Stripe(stripe_api_key);

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: "#32325d",
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: "antialiased",
        fontSize: "16px",
        "::placeholder": {
          color: "#aab7c4",
        },
      },
      invalid: {
        color: "#fa755a",
        iconColor: "#fa755a",
      },
    };

    // Create an instance of the card Element.
    var card = elements.create("card", { style: style });

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount("#card-element");

    // Handle real-time validation errors from the card Element.
    card.addEventListener("change", function (event) {
      var displayError = document.getElementById("card-errors");
      if (event.error) {
        $("#stripe_pay_btn").attr("disabled", false);
        $("#stripe_pay_btn").html("Confirm and Pay");
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = "";
      }
    });

    // Handle form submission.
    var form = document.getElementById("payment-form");
    form.addEventListener("submit", function (event) {
      event.preventDefault();
      $("#stripe_pay_btn").attr("disabled", true);
      $("#stripe_pay_btn").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      stripe
        .createPaymentMethod({
          type: "card",
          card: card,
        })
        .then(stripePaymentMethodHandler);
    });

    function stripePaymentMethodHandler(result) {
      if (result.error) {
        $("#card-errors").text(result.error.message);
        $("#stripe_pay_btn").attr("disabled", false);
        $("#stripe_pay_btn").html("Confirm and Pay");
        // Show error in payment form
      } else {
        // Otherwise send paymentMethod.id to your server

        var payment_method = $("#myself:checked").val();

        $.ajax({
          url: base_url + "book_appoinments/stripe_payment",
          data: {
            payment_method_id: result.paymentMethod.id,
            payment_method: payment_method,
          },
          type: "POST",
          dataType: "JSON",
          beforeSend: function () {
            $("#stripe_pay_btn").attr("disabled", true);
            $("#stripe_pay_btn").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (response) {
            handleServerResponse(response);
          },
          error: function (error) {
            console.log(error);
          },
        });
      }
    }

    function handleServerResponse(response) {
      if (response.status == "500") {
        toastr.error(response.message);
        $("#stripe_pay_btn").attr("disabled", false);
        $("#stripe_pay_btn").html("Confirm and Pay");
      } else if (response.status == "201") {
        // Use Stripe.js to handle required card action
        stripe
          .handleCardAction(response.payment_intent_client_secret)
          .then(handleStripeJsResult);
      } else {
        // print notification message here
        toastr.success(lg_transaction_suc);
        setTimeout(function () {
          window.location.href =
            base_url + "appoinment-success/" + response.appointment_id;
        }, 2000);
      }
    }

    function handleStripeJsResult(result) {
      if (result.status == "500") {
        toastr.error(result.message);
      } else {
        // The card action has been handled
        // The PaymentIntent can be confirmed again on the server

        var payment_method = $("#myself:checked").val();

        $.ajax({
          url: base_url + "book_appoinments/stripe_payment",
          data: {
            payment_intent_id: result.paymentIntent.id,
            payment_method: payment_method,
          },
          type: "POST",
          dataType: "JSON",
          beforeSend: function () {
            $("#stripe_pay_btn").attr("disabled", true);
            $("#stripe_pay_btn").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (response) {
            handleServerResponse(response);
          },
          error: function (error) {
            console.log(error);
          },
        });
      }
    }

    // Submit the form with the token ID.

    $(document).ready(function () {
      $("#my_book_appoinment").click(function (e) {
        $.ajax({
          url: base_url + "book_appoinments/clinic_appoinments",

          data: $("#payment_formid").serialize(),

          type: "POST",

          dataType: "JSON",

          beforeSend: function () {
            $("#pay_button").attr("disabled", true);
            $("#pay_button").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (response) {
            if (response.status == "200") {
              toastr.success(lg_transaction_suc);
              setTimeout(function () {
                window.location.href =
                  base_url +
                  "book_appoinments/payment_sucess/" +
                  response.appointment_id;
              }, 2000);
            } else {
              toastr.error(lg_transaction_fai1);
              setTimeout(function () {
                window.location.href = base_url + "dashboard";
              }, 2000);
            }
          },

          error: function (error) {
            console.log(error);
          },
        });

        e.preventDefault();
      });
    });

    $(document).ready(function () {
      $("input[type=radio][name=payment_methods]").change(function () {
        if (this.value == "Card Payment") {
          $(".stripe_payment").show();
          $(".paypal_payment").hide();
          $(".clinic_payment").hide();
          $(".razorpay_payment").hide();
          $(".cybersource_payment").hide();
          $("#payment_method").val("Card Payment");
        } else if (this.value == "PayPal") {
          $(".stripe_payment").hide();
          $(".paypal_payment").show();
          $(".clinic_payment").hide();
          $(".razorpay_payment").hide();
          $(".cybersource_payment").hide();
          $("#payment_method").val("Card Payment");
        } else if (this.value == "Pay on Arrive") {
          $(".stripe_payment").hide();
          $(".paypal_payment").hide();
          $(".clinic_payment").show();
          $(".razorpay_payment").hide();
          $(".cybersource_payment").hide();
          $("#payment_method").val("Pay on Arrive");
        } else if (this.value == "Razorpay") {
          $(".stripe_payment").hide();
          $(".paypal_payment").hide();
          $(".clinic_payment").hide();
          $(".razorpay_payment").show();
          $(".cybersource_payment").hide();
          $("#payment_method").val("Card Payment");
        } else if (this.value == "Cybersource") {
          $(".stripe_payment").hide();
          $(".paypal_payment").hide();
          $(".clinic_payment").hide();
          $(".razorpay_payment").hide();
          $(".cybersource_payment").show();
          $("#payment_method").val("Card Payment");
        } else {
          $(".stripe_payment").hide();
          $(".paypal_payment").hide();
          $(".clinic_payment").hide();
          $(".razorpay_payment").hide();
          $("#payment_method").val("");
        }
      });
    });
  }
}

if (modules == "doctor" || modules == "patient") {
  if (pages == "doctor_dashboard" || pages == "patient_dashboard") {
    /*function email_verification()
        {
            $.get(base_url + 'dashboard/send_verification_email', function (data) {
                toastr.success(lg_activation_mail);
            });
        }*/
  }

  if (pages == "doctor_dashboard") {
    console.log("f");
    function appoinments_table_func() {
      var appoinment_table;

      appoinment_table = $("#appoinments_table").DataTable({
        ordering: true,
        processing: true, //Feature control the processing indicator.
        bnDestroy: true,
        serverSide: true, //Feature control DataTables' server-side processing mode.
        order: [], //Initial no order.
        /* "language": {                
                 "infoFiltered": ""
             },*/
        // Load data for the table's content from an Ajax source
        ajax: {
          url: base_url + "dashboard/appoinments_list",
          type: "POST",
          data: function (data) {
            data.type = $("#type").val();
          },
          error: function () {},
        },

        //Set column definition initialisation properties.
        columnDefs: [
          {
            targets: [0], //first column / numbering column
            orderable: false, //set not orderable
          },
        ],
      });
    }
    appoinments_table_func();

    function appoinments_table(type) {
      $("#type").val(type);
      // appoinment_table.ajax.reload(null, false); //reload datatable ajax
      // appoinment_table.destroy();

      if ($.fn.DataTable.isDataTable("#appoinments_table")) {
        $("#appoinments_table").DataTable().destroy();
      }
      $("#appoinments_table tbody").empty();

      appoinments_table_func();
    }

    appoinments_table(1);

    function show_appoinments_modal(app_date, book_date, amount, type) {
      $(".app_date").html(app_date);
      $(".book_date").html(book_date);
      $(".amount").html(amount);
      $(".type").html(type);
      $("#appoinments_details").modal("show");
    }

    function conversation_status(id, status) {
      if (status == 1) {
        $(".app-modal-title").html(lg_accept);
        $("#app-modal-title").html(lg_accept);
        $("#appoinments_status").val("1");
        $("#appoinments_id").val(id);
      }

      if (status == 0) {
        $(".app-modal-title").html(lg_cancel);
        $("#app-modal-title").html(lg_cancel);
        $("#appoinments_status").val("0");
        $("#appoinments_id").val(id);
      }

      $("#appoinments_status_modal").modal("show");
    }

    function change_status() {
      var appoinments_id = $("#appoinments_id").val();
      var appoinments_status = $("#appoinments_status").val();
      $("#change_btn").attr("disabled", true);
      $("#change_btn").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      $.post(
        base_url + "appoinments/change_status",
        {
          appoinments_id: appoinments_id,
          appoinments_status,
          appoinments_status,
        },
        function (res) {
          my_appoinments(0);

          $("#change_btn").attr("disabled", false);
          $("#change_btn").html(lg_yes);
          $("#appoinments_status_modal").modal("hide");
        }
      );
    }
  }

  if (pages == "my_patients") {
    my_patient(0);
    function my_patient(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var page = $("#page_no_hidden").val();

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "my_patients/patient_list",
        type: "POST",
        data: { page: page },
        beforeSend: function () {
          // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            var obj = $.parseJSON(response);
            if (obj.data.length >= 1) {
              var html = "";
              $(obj.data).each(function () {
                html +=
                  '<div class="col-md-6 col-lg-4 col-xl-3">' +
                  '<div class="card widget-profile pat-widget-profile">' +
                  '<div class="card-body">' +
                  '<div class="pro-widget-content">' +
                  '<div class="profile-info-widget">' +
                  '<a href="' +
                  base_url +
                  "mypatient-preview/" +
                  this.userid +
                  '" class="booking-doc-img">' +
                  '<img src="' +
                  this.profileimage +
                  '" alt="User Image">' +
                  "</a>" +
                  '<div class="profile-det-info">' +
                  '<h3><a href="' +
                  base_url +
                  "mypatient-preview/" +
                  this.userid +
                  '">' +
                  this.first_name +
                  " " +
                  this.last_name +
                  "</a></h3>" +
                  '<div class="patient-details">' +
                  "<h5><b>" +
                  lg_patient_id +
                  " :</b> #PT00" +
                  this.user_id +
                  "</h5>" +
                  '<h5 class="mb-0"><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</h5>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  '<div class="patient-info">' +
                  "<ul>" +
                  "<li>" +
                  lg_phone +
                  " <span>" +
                  this.mobileno +
                  "</span></li>" +
                  "<li>" +
                  lg_age +
                  " <span>" +
                  this.age +
                  ", " +
                  this.gender +
                  "</span></li>" +
                  "<li>" +
                  lg_blood_group +
                  " <span>" +
                  this.blood_group +
                  "</span></li>" +
                  "</ul>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>";
              });

              if (obj.current_page_no == 1) {
                $("#patients-list").html(html);
              } else {
                $("#patients-list").append(html);
              }

              if (obj.count == 0) {
                $("#load_more_btn").addClass("d-none");
                $("#no_more").removeClass("d-none");
                return false;
              }

              if (obj.current_page_no == 1 && obj.count < 8) {
                $("page_no_hidden").val(1);
                $("#load_more_btn").addClass("d-none");
                $("#no_more").removeClass("d-none");
                return false;
              }

              if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
                $("#load_more_btn").removeClass("d-none");
                $("#no_more").addClass("d-none");
              } else {
                $("#load_more_btn").addClass("d-none");
                $("#no_more").removeClass("d-none");
              }
            } else {
              var html =
                '<div class="appointment-list">' +
                '<div class="profile-info-widget">' +
                "<p>" +
                lg_no_patients_fou +
                "</p>" +
                "</div>" +
                "</div>";
              $("#patients-list").html(html);
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      my_patient(1);
    });
  }

  if (pages == "mypatient_preview" || pages == "patient_dashboard") {
    var appoinment_table;
    appoinment_table = $("#appoinment_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "my_patients/appoinments_list",
        type: "POST",
        data: function (data) {
          data.patient_id = $("#patient_id").val();
        },
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    $(document).on("change", ".appointment_status", function () {
      var id = $(this).attr("id");
      var status = $(this).val();
      $.post(
        base_url + "my_patients/change_appointment_status",
        { id: id, status: status },
        function (data) {
          toastr.success(lg_status_updated_);
          appoinment_table.ajax.reload(null, false);
        }
      );
    });

    function appoinments_table() {
      appoinment_table.ajax.reload(null, false);
    }

    var prescription_table;
    prescription_table = $("#prescription_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "my_patients/prescriptions_list",
        type: "POST",
        data: function (data) {
          data.patient_id = $("#patient_id").val();
        },
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    function prescriptions_table() {
      prescription_table.ajax.reload(null, false);
    }

    function view_prescription(pre_id) {
      $(".overlay").show();
      $.post(
        base_url + "my_patients/get_prescription_details",
        { pre_id: pre_id },
        function (res) {
          var obj = jQuery.parseJSON(res);
          var table =
            '<table class="table table-bordered table-hover">' +
            "<thead>" +
            "<tr>" +
            "<th>" +
            lg_sno +
            "</th>" +
            "<th>" +
            lg_drug_name +
            "</th>" +
            "<th>" +
            lg_quantity +
            "</th>" +
            "<th>" +
            lg_type +
            "</th>" +
            "<th>" +
            lg_days +
            "</th>" +
            "<th>" +
            lg_time +
            "</th>" +
            "</tr>" +
            "<tbody>";
          var i = 1;
          $(obj).each(function () {
            var j = i++;

            table +=
              "<tr>" +
              "<td>" +
              j +
              "</td>" +
              "<td>" +
              this.drug_name +
              "</td>" +
              "<td>" +
              this.qty +
              "</td>" +
              "<td>" +
              this.type +
              "</td>" +
              "<td>" +
              this.days +
              "</td>" +
              "<td>" +
              this.time +
              "</td>" +
              "</tr>";
          });
          table +=
            "</tbody>" +
            "</table>" +
            '<div class="float-right">' +
            '<img src="' +
            base_url +
            obj[0].img +
            '" style="width:150px"><br>' +
            "( " +
            lg_dr +
            " " +
            obj[0].doctor_name.charAt(0).toUpperCase() +
            obj[0].doctor_name.slice(1) +
            " ) <br>" +
            "<div>" +
            lg_doctor_signatur +
            "</div><br>" +
            "</div>";
          $("#patient_name").text(obj[0].patient_name);
          $("#view_date").text(obj[0].prescription_date);
          $(".view_title").text(lg_prescription);
          $(".view_details").html(table);
          $("#view_modal").modal("show");
          $(".overlay").hide();
        }
      );
    }

    function delete_prescription(id) {
      $("#delete_id").val(id);
      $("#delete_table").val("prescription");
      $("#delete_title").text(lg_prescription);
      $("#delete_modal").modal("show");
    }

    var billing_table;
    billing_table = $("#billing_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "my_patients/billing_list",
        type: "POST",
        data: function (data) {
          data.patient_id = $("#patient_id").val();
        },
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0, 2, 4], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    function billings_table() {
      billing_table.ajax.reload(null, false);
    }

    function view_billing(id) {
      $(".overlay").show();
      $.post(
        base_url + "my_patients/get_billing_details",
        { id: id },
        function (res) {
          var obj = jQuery.parseJSON(res);
          var table =
            '<table class="table table-bordered table-hover">' +
            "<thead>" +
            "<tr>" +
            "<th>" +
            lg_sno +
            "</th>" +
            "<th>" +
            lg_description +
            "</th>" +
            "<th>" +
            lg_amount +
            "</th>" +
            "</tr>" +
            "<tbody>";
          var i = 1;
          $(obj).each(function () {
            var j = i++;
            table +=
              "<tr>" +
              "<td>" +
              j +
              "</td>" +
              "<td>" +
              this.name +
              "</td>" +
              "<td>" +
              this.amount +
              "</td>" +
              "</tr>";
          });
          table +=
            "</tbody>" +
            "</table>" +
            '<div class="float-right">' +
            '<img src="' +
            base_url +
            obj[0].img +
            '" style="width:150px"><br>' +
            "( " +
            lg_dr +
            " " +
            obj[0].doctor_name.charAt(0).toUpperCase() +
            obj[0].doctor_name.slice(1) +
            " ) <br>" +
            "<div>" +
            lg_doctor_signatur +
            "</div><br>" +
            "</div>";
          $("#patient_name").text(obj[0].patient_name);
          $("#view_date").text(obj[0].billing_date);
          $(".view_title").text(lg_doctor_billing);
          $(".view_details").html(table);
          $("#view_modal").modal("show");
        }
      );
    }

    function delete_billing(id) {
      $("#delete_id").val(id);
      $("#delete_table").val("billing");
      $("#delete_title").text(lg_bill4);
      $("#delete_modal").modal("show");
    }

    function view_dec(id) {
      $.ajax({
        type: "POST",
        url: base_url + "my_patients/view_dec",
        data: { id: id },
        beforeSend: function () {},
        success: function (res) {
          $("#med_desc").html(res);
          $("#show_desc_medical_records").modal("show");
        },
      });
    }

    $("#medical_records_form").submit(function (e) {
      e.preventDefault();
      var oFile = document.getElementById("user_files_mr").files[0];
      var medical_record_id = $("#medical_record_id").val();

      if (
        !document.getElementById("user_files_mr").files[0] &&
        medical_record_id == ""
      ) {
        toastr.warning(lg_please_upload_m);
        return false;
      }
      if (oFile && medical_record_id == "") {
        if (oFile.size > 25097152) {
          // 25 mb for bytes.

          toastr.warning(lg_file_size_must_);
          return false;
        }
        const fileType = oFile["type"];

        const validImageTypes = [
          "image/gif",
          "image/jpeg",
          "image/png",
          "image/jpg",
          "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
          "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
          "application/pdf",
        ];
        if (!validImageTypes.includes(fileType)) {
          toastr.error(lg_please_upload_i1);
          return false;
        }
      }

      var formData = new FormData($("#medical_records_form")[0]);
      $.ajax({
        url: base_url + "my_patients/upload_medical_records",
        type: "POST",
        data: formData,
        beforeSend: function () {
          if (oFile) {
            $("#medical_btn").attr("disabled", true);
            $("#medical_btn").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          }
        },
        success: function (res) {
          $("#medical_btn").attr("disabled", false);
          $("#medical_btn").html(lg_submit);
          $("#add_medical_records").modal("hide");
          var obj = jQuery.parseJSON(res);
          if (obj.status === 500) {
            toastr.warning(obj.msg);
            $("#user_files_mr").val("");
          } else {
            $("#medical_records_form")[0].reset();
            toastr.success(obj.msg);
            medical_records_table();
          }
        },
        error: function (data) {},
        cache: false,
        contentType: false,
        processData: false,
      });
      return false;
    });

    var medical_record_table;
    medical_record_table = $("#medical_records_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "my_patients/medical_records_list",
        type: "POST",
        data: function (data) {
          data.patient_id = $("#patient_id").val();
        },
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    function medical_records_table() {
      medical_record_table.ajax.reload(null, false);
    }

    function delete_medical_records(id) {
      $("#delete_id").val(id);
      $("#delete_table").val("medical_records");
      $("#delete_title").text(lg_medical_records);
      $("#delete_modal").modal("show");
    }

    function delete_details() {
      var id = $("#delete_id").val();
      var delete_table = $("#delete_table").val();
      $("#delete_btn").attr("disabled", true);
      $("#delete_btn").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      $.post(
        base_url + "my_patients/delete_details",
        { id: id, delete_table, delete_table },
        function (res) {
          if (delete_table == "prescription");
          {
            prescriptions_table();
          }

          if (delete_table == "medical_records");
          {
            medical_records_table();
          }

          if (delete_table == "billing");
          {
            billings_table();
          }

          $("#delete_btn").attr("disabled", false);
          $("#delete_btn").html(lg_yes);
          $("#delete_modal").modal("hide");
          if (delete_table == "prescription") {
            toastr.error(lg_prescription_de);
          }
          if (delete_table == "medical_records") {
            toastr.error(lg_medical_records4);
          }
          if (delete_table == "billing") {
            toastr.error(lg_billing_detail_1);
          }
        }
      );
    }

    function edit_medi_rec(id) {
      $.ajax({
        url: base_url + "my_patients/get_medical_records",
        type: "POST",
        data: { medical_rec_id: id },
        success: function (res) {
          var obj = $.parseJSON(res);
          $(obj.data).each(function () {
            $("#patient_id").val(this.patient_id);
            $("#description").html(this.description);
            $("#medical_record_id").val(this.id);
            $("#show_med_rec_url").attr("href", base_url + this.file_name);
            $("#show_med_rec_url").css("display", "block");
          });
        },
        error: function (data) {
          alert("error");
        },
      });

      $("#add_medical_records").modal("show");
    }
    $(".add-new-btn").click(function () {
      $("#medical_records_form").trigger("reset");
    });
  }

  if (
    pages == "add_prescription" ||
    pages == "edit_prescription" ||
    pages == "add_billing" ||
    pages == "edit_billing"
  ) {
    var wrapper = document.getElementById("signature-pad"),
      clearButton = wrapper.querySelector("[data-action=clear]"),
      saveButton = wrapper.querySelector("[data-action=save]"),
      canvas = wrapper.querySelector("canvas"),
      signaturePad;

    function resizeCanvas() {
      var ratio = window.devicePixelRatio || 1;
      canvas.width = canvas.offsetWidth * ratio;
      canvas.height = canvas.offsetHeight * ratio;
      canvas.getContext("2d").scale(ratio, ratio);
    }

    signaturePad = new SignaturePad(canvas);
    clearButton.addEventListener("click", function (event) {
      signaturePad.clear();
    });

    saveButton.addEventListener("click", function (event) {
      if (signaturePad.isEmpty()) {
        console.log("You should sign!");
      } else {
        $.ajax({
          type: "POST",
          url: base_url + "my_patients/insert_signature",
          data: { image: signaturePad.toDataURL(), rowno: $("#rowno").val() },
          beforeSend: function () {
            $("#save2").attr("disabled", true);
            $("#save2").html(
              '<div class="spinner-border text-light" role="status"></div>'
            );
          },
          success: function (res) {
            $("#save2").attr("disabled", false);
            $("#save2").html(lg_save);
            signaturePad.clear();
            $("#sign-modal").modal("hide");
            $(".doctor_signature").html("");
            $(".doctor_signature").html(res);
            $(".doctor_signature").removeClass("doctor_signature");
          },
        });
      }
    });

    $(".doctor_signature").click(function () {
      show_modal();
    });

    function show_modal() {
      $("#sign-modal").modal("show");
      const context = canvas.getContext("2d");
      context.clearRect(0, 0, 460, 318);
      $("#edit").addClass("doctor_signature");
    }
    $(".clear_sign").click(function () {
      $("#edit").find("img").remove();
      $("#signature_id").val(0);
      $("#edit").append("Click here to Sign");
    });

    function delete_row(id) {
      $("#delete_" + id).remove();
      toastr.error(lg_delete_rw);
      //console.log(row_count);
    }

    function isNumberKey(evt) {
      var charCode = evt.which ? evt.which : event.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) return false;

      return true;
    }
  }

  if (pages == "add_prescription" || pages == "edit_prescription") {
    function add_more_row() {
      var hidden_count = $("#hidden_count").val();
      var total = Number(hidden_count) + 1;
      $("#hidden_count").val(total);

      var append_rows =
        '<tr id="delete_' +
        total +
        '">' +
        '<td><input type="text" name="drug_name[]" id="drug_name' +
        total +
        '" class="form-control filter-form inputcls "></td>' +
        '<td style="min-width: 100px; max-width: 100px;"><input type="text" onkeypress="return isNumberKey(event)" name="qty[]" id="qty' +
        total +
        '" class="form-control filter-form text inputcls" maxlength="4"></td>' +
        '<td><select class="form-control filter-form inputcls" name="type[]" id="type' +
        total +
        '">' +
        '<option value="">' +
        lg_select_type +
        "</option>" +
        '<option value="Before Food">' +
        lg_before_food +
        "</option>" +
        '<option value="After Food">' +
        lg_after_food +
        "</option>" +
        "</select>" +
        "</td>" +
        '<td style="min-width: 100px; max-width: 100px;"><input onkeypress="return isNumberKey(event)" type="text" name="days[]" id="days' +
        total +
        '" class="form-control filter-form text inputcls" maxlength="4" autocomplete="off"></td>' +
        '<td class="checkbozcls">' +
        '<div class="row">' +
        '<div class="col-md-6">' +
        '<input type="checkbox" name="time[' +
        total +
        '][]" value="Morning" id="morning' +
        total +
        '"><label for="morning' +
        total +
        '">&nbsp;&nbsp;' +
        lg_morning +
        "</label>" +
        "</div>" +
        '<div class="col-md-6">' +
        '<input type="checkbox" name="time[' +
        total +
        '][]" value="Afternoon" id="afternoon' +
        total +
        '"><label for="afternoon' +
        total +
        '">&nbsp;&nbsp;' +
        lg_afternoon +
        "</label>" +
        "</div>" +
        "</div>" +
        '<div class="row">' +
        '<div class="col-md-6">' +
        '<input type="checkbox" name="time[' +
        total +
        '][]" value="Evening" id="evening' +
        total +
        '"><label for="evening' +
        total +
        '">&nbsp;&nbsp;' +
        lg_evening +
        "</label>" +
        "</div>" +
        '<div class="col-md-6">' +
        '<input type="checkbox" name="time[' +
        total +
        '][]" value="Night" id="night' +
        total +
        '"><label for="night' +
        total +
        '">&nbsp;&nbsp;' +
        lg_night +
        "</label>" +
        "</div>" +
        "</div>" +
        '<input type="hidden" value="' +
        total +
        '" name="rowValue[]">' +
        "</td>" +
        "<td>" +
        '<a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(' +
        total +
        ')"><i class="far fa-trash-alt"></i></a>' +
        "</td>" +
        "</tr>";

      $("#add_more_rows").append(append_rows);
    }

    $(document).ready(function () {
      $("#add_prescription").validate({
        rules: {
          // "drug_name[]": "required",
          // "qty[]": "required",
          // "type[]": "required",
          // "days[]": "required",
          // "time[]": "required"
        },
        messages: {
          // "drug_name[]": lg_please_enter_dr,
          // "qty[]": lg_please_enter_qt,
          // "type[]": lg_please_select_t1,
          // "days[]": lg_please_enter_da,
          // "time[]": lg_please_select_t2
        },
        submitHandler: function (form) {
          /*Fields from Ajax Validation*/
          var inputerr = 0;
          var checkerr = 0;
          var checkerr1 = 0;
          $(".inputcls").map(function () {
            if ($.trim($(this).val()) == "") {
              $(this).attr("style", "border-color:red");
              $(".select2-selection").attr("style", "border-color:red");
              inputerr++;
            } else {
              $(this).removeAttr("style");
              $(".select2-selection").removeAttr("style");
            }
          });
          if (inputerr > 0) {
            toastr.error(lg_please_enter_va5);
            return false;
          } else {
            $("input[name='rowValue[]']").each(function () {
              var checkedNum = $(
                "input[name='time[" + this.value + "][]']:checked"
              ).length;
              if (checkedNum == 0) {
                checkerr++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_please_select_t2);
              return false;
            }
            // For Drug name Special char and number validation start
            $("input[name='drug_name[]']").each(function () {
              var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
              var drug_name = $("input[name='drug_name[]']").val();
              if (!characterReg.test(drug_name)) {
                checkerr++;
              }
              if (drug_name.length > 30) {
                checkerr1++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_no_special_char);
              return false;
            }
            if (checkerr1 > 0) {
              toastr.error(lg_drug_name_chara);
              return false;
            }
            // For Drug name Special char and number validation end

            // For Drug name Special char and number validation
            $("input[name='qty[]']").each(function () {
              var characterReg1 = /^\s*[0-9,\s]+\s*$/;
              var qty = $("input[name='qty[]']").val();
              if (!characterReg1.test(qty)) {
                checkerr++;
              }
              if (qty.length > 30) {
                checkerr1++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_numbers_only_al);
              return false;
            }
            if (checkerr1 > 0) {
              toastr.error(lg_qty_length_shou);
              return false;
            }
          }

          /*Fields from Ajax Validation*/

          var signature_id = $("#signature_id").val();
          if (signature_id == 0) {
            /* Signature validation */
            toastr.error(lg_please_sign_to_);
            return false;
          }

          $.ajax({
            url: base_url + "my_patients/save_prescription",
            data: $("#add_prescription").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#prescription_save_btn").attr("disabled", true);
              $("#prescription_save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#prescription_save_btn").attr("disabled", false);
              $("#prescription_save_btn").html(lg_save);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href =
                    base_url + "mypatient-preview/" + obj.patient_id;
                }, 3000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });

      $("#update_prescription").validate({
        rules: {
          "drug_name[]": "required",
          "qty[]": "required",
          "type[]": "required",
          "days[]": "required",
          "time[]": "required",
        },
        messages: {
          "drug_name[]": lg_please_enter_dr,
          "qty[]": lg_please_enter_qt,
          "type[]": lg_please_select_t1,
          "days[]": lg_please_enter_da,
          "time[]": lg_please_select_t2,
        },
        submitHandler: function (form) {
          var signature_id = $("#signature_id").val();
          if (signature_id == 0) {
            /* Signature validation */
            toastr.error(lg_please_sign_to_);
            return false;
          }

          /*Fields from Ajax Validation*/
          var inputerr = 0;
          var checkerr = 0;
          $(".inputcls").map(function () {
            if ($.trim($(this).val()) == "") {
              $(this).attr("style", "border-color:red");
              inputerr++;
            } else {
              $(this).removeAttr("style");
            }
          });
          if (inputerr > 0) {
            toastr.error(lg_please_enter_va5);
            return false;
          } else {
            $("input[name='rowValue[]']").each(function () {
              var checkedNum = $(
                "input[name='time[" + this.value + "][]']:checked"
              ).length;
              if (checkedNum == 0) {
                checkerr++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_please_select_t2);
              return false;
            }
          }
          /*Fields from Ajax Validation*/

          $.ajax({
            url: base_url + "my_patients/update_prescription",
            data: $("#update_prescription").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#prescription_update_btn").attr("disabled", true);
              $("#prescription_update_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#prescription_update_btn").attr("disabled", false);
              $("#prescription_update_btn").html(lg_save);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href =
                    base_url + "mypatient-preview/" + obj.patient_id;
                }, 2000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    });
  }

  if (pages == "add_billing" || pages == "edit_billing") {
    function add_more_row() {
      var hidden_count = $("#hidden_count").val();
      var total = Number(hidden_count) + 1;
      $("#hidden_count").val(total);
      var append_rows =
        '<tr id="delete_' +
        total +
        '">' +
        "<td>" +
        '<input type="text" name="name[]" id="name' +
        total +
        '" class="form-control filter-form inputcls" >' +
        "<td>" +
        '<input type="decimal" name="amount[]" onkeypress="return isNumberKey(event)" id="amount' +
        total +
        '" class="form-control filter-form inputcls" >' +
        "</td>" +
        '<td><a href="javascript:void(0)" class="btn bg-danger-light trash" onclick="delete_row(' +
        total +
        ')"><i class="far fa-trash-alt"></i></a></td>' +
        "</tr>";

      $("#add_more_rows").append(append_rows);
    }

    $(document).ready(function () {
      $("#add_billing").validate({
        rules: {
          /*"name[]": { required: true, 
                                SpecCharValidate: true, 
                                maxlength:25 },
                    "amount[]": { required: true},*/
        },
        messages: {
          /*"name[]": { required : lg_please_enter_na, 
                                SpecCharValidate: "No Special Chars/Numbers allowed ", 
                                 maxlength: "Maximum length should be within 25 characters"
                               }, 
                    "amount[]": {required: lg_please_enter_am } */
        },
        submitHandler: function (form) {
          /*Fields from Ajax Validation*/
          var inputerr = 0;
          var checkerr = 0;
          var checkerr1 = 0;
          $(".inputcls").map(function () {
            if ($.trim($(this).val()) == "") {
              $(this).attr("style", "border-color:red");
              $(".select2-selection").attr("style", "border-color:red");
              inputerr++;
            } else {
              $(this).removeAttr("style");
              $(".select2-selection").removeAttr("style");
            }
          });
          if (inputerr > 0) {
            toastr.error(lg_please_enter_va5);
            return false;
          } else {
            // For Name Special char and number validation start
            $("input[name='name[]']").each(function () {
              var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
              var name = $("input[name='name[]']").val();
              if (!characterReg.test(name)) {
                checkerr++;
              }
              if (name.length > 30) {
                checkerr1++;
              }
            });
            if (checkerr > 0) {
              toastr.error(lg_no_special_char);
              return false;
            }
            if (checkerr1 > 0) {
              toastr.error(lg_name_character_);
              return false;
            }
            // For Drug name Special char and number validation end

            // For Drug name Special char and number validation
            $("input[name='amount[]']").each(function () {
              //var characterReg1 = /^\s*[0-9,\s]+\s*$/;
              var amt = $("input[name='amount[]']").val();
              //if (!characterReg1.test(amt)) {checkerr++;}
              if (amt.length > 30) {
                checkerr1++;
              }
            });
            /*if (checkerr>0) {
                            toastr.error("Numbers Only allowed");	
                            return false;
                        }*/
            if (checkerr1 > 0) {
              toastr.error(lg_amount_should_b);
              return false;
            }
          }
          /*Fields from Ajax Validation*/

          var signature_id = $("#signature_id").val();
          if (signature_id == 0) {
            /* Signature validation */
            toastr.error(lg_please_sign_to_);
            return false;
          }
          $.ajax({
            url: base_url + "my_patients/save_billing",
            data: $("#add_billing").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#bill_save_btn").attr("disabled", true);
              $("#bill_save_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#bill_save_btn").attr("disabled", false);
              $("#bill_save_btn").html(lg_save);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href =
                    base_url + "mypatient-preview/" + obj.patient_id;
                }, 3000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
      $.validator.addMethod(
        "SpecCharValidate",
        function (value, element) {
          var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
          if (!characterReg.test(value)) {
            return false;
          } else {
            return true;
          }
        },
        "No Special Chars or Numbers Allowed in the City Name"
      );

      $("#update_billing").validate({
        rules: {
          "name[]": "required",
          "amount[]": "required",
        },
        messages: {
          "name[]": lg_please_enter_na,
          "amount[]": lg_please_enter_am,
        },
        submitHandler: function (form) {
          var signature_id = $("#signature_id").val();
          if (signature_id == 0) {
            /* Signature validation */
            toastr.error(lg_please_sign_to_);
            return false;
          }
          $.ajax({
            url: base_url + "my_patients/update_billing",
            data: $("#update_billing").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#billing_update_btn").attr("disabled", true);
              $("#billing_update_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#billing_update_btn").attr("disabled", false);
              $("#billing_update_btn").html(lg_update);

              var obj = JSON.parse(res);

              if (obj.status === 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href =
                    base_url + "mypatient-preview/" + obj.patient_id;
                }, 3000);
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    });
  }
}

if (
  modules == "doctor" ||
  modules == "patient" ||
  modules == "lab" ||
  modules == "pharmacy"
) {
  function email_verification() {
    $.get(base_url + "dashboard/send_verification_email", function (data) {
      toastr.success(lg_activation_mail);
    });
  }

  $("#change_password").validate({
    rules: {
      currentpassword: {
        required: true,
        remote: {
          url: base_url + "profile/check_currentpassword",
          type: "post",
          data: {
            currentpassword: function () {
              return $("#currentpassword").val();
            },
          },
        },
      },

      password: {
        required: true,
        minlength: 6,
        maxlength: 20,
      },
      confirm_password: {
        required: true,
        equalTo: "#password",
        maxlength: 20,
      },
    },
    messages: {
      currentpassword: {
        required: lg_please_enter_cu,
        remote: lg_your_current_pa,
      },
      password: {
        required: lg_please_enter_new_pa,
        minlength: lg_your_password_m,
        maxlength: lg_password_max_length_20,
      },
      confirm_password: {
        required: lg_please_enter_co,
        equalTo: lg_your_password_d,
        maxlength: lg_confirm_password_max_length_20,
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: base_url + "profile/password_update",
        data: $("#change_password").serialize(),
        type: "POST",
        beforeSend: function () {
          $("#change_password_btn").attr("disabled", true);
          $("#change_password_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (res) {
          $("#change_password_btn").attr("disabled", false);
          $("#change_password_btn").html(lg_change_password);
          var obj = JSON.parse(res);

          if (obj.status === 200) {
            $("#change_password")[0].reset();
            toastr.success(obj.msg);
          } else {
            toastr.error(obj.msg);
          }
        },
      });
      return false;
    },
  });
  if (pages == "change_password") {
    $(document).ready(function () {
      const togglecurrentpassword = document.querySelector(
        "#togglecurrentpassword"
      );
      const currentpassword = document.querySelector("#currentpassword");

      togglecurrentpassword.addEventListener("click", function (e) {
        // toggle the type attribute
        const type1 =
          currentpassword.getAttribute("type") === "password"
            ? "text"
            : "password";
        currentpassword.setAttribute("type", type1);
        // toggle the eye slash icon
        this.classList.toggle("fa-eye-slash");
      });

      const togglenewpassword = document.querySelector("#togglenewpassword");
      const password = document.querySelector("#password");

      togglenewpassword.addEventListener("click", function (e) {
        // toggle the type attribute
        const type2 =
          password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type2);
        // toggle the eye slash icon
        this.classList.toggle("fa-eye-slash");
      });

      const toggleconfirmpassword = document.querySelector(
        "#toggleconfirmpassword"
      );
      const confirm_password = document.querySelector("#confirm_password");

      toggleconfirmpassword.addEventListener("click", function (e) {
        // toggle the type attribute
        const type3 =
          confirm_password.getAttribute("type") === "password"
            ? "text"
            : "password";
        confirm_password.setAttribute("type", type3);
        // toggle the eye slash icon
        this.classList.toggle("fa-eye-slash");
      });
    });
  }
}

if (modules == "doctor") {
  if (pages == "appoinments") {
    setInterval(function () {
      // my_appoinments(0);
      var loadval = $("#page_no_hidden").val();
      my_appoinments(loadval);
    }, 1000);

    function my_appoinments(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var page = $("#page_no_hidden").val();

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "appoinments/doctor_appoinments_list",
        type: "POST",
        data: { page: page },
        beforeSend: function () {
          // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            var obj = $.parseJSON(response);

            if (obj.current_page_no == 1) {
              $("#appointment-list").html(obj.data);
            } else {
              // $("#appointment-list").append(obj.data);
              $("#appointment-list").html(obj.data);
            }

            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 8) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      my_appoinments(1);
    });

    function appointments_modal() {
      console.log("here");

      $("#reschedule_details").modal("show");
    }

    function show_appoinments_modal(app_date, book_date, amount, type, id) {
      $(".app_date").html(app_date);
      $(".book_date").html(book_date);
      $(".amount").html(amount);
      $(".type").html(type);
      $("#app_id").val(id);
      $("#assign_doc").html("");

      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_hospital_doctor",

        success: function (data) {
          /*get response as json */
          var obj = jQuery.parseJSON(data);
          console.log(obj.length);
          if (obj.length == 0) {
            $("#assign_doc").css("display", "none");
            $("#assign_doc_err").html(
              "No doctors are found for this clinic...!"
            );
            $(".add_doc_btn1").css("display", "block");
          } else {
            $("#assign_doc").css("display", "block");
            $(".add_doc_btn1").css("display", "none");
            var option = $("<option />");
            // option.attr('value', '0').text('Select Doctor');
            $("#assign_doc").append(option);
            $(obj).each(function () {
              var option = $("<option />");
              option.attr("value", this.value).text(this.label);
              $("#assign_doc").append(option);
            });
            $("#assign_doc").val(doc_id);
            /*ends */
          }
        },
      });

      $("#appoinments_details").modal("show");
    }

    function assign_doctor(id) {
      $("#app_id_assign").val(id);
      $("#assign_doc").html("");

      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_hospital_doctor",

        success: function (data) {
          /*get response as json */
          var obj = jQuery.parseJSON(data);
          console.log(obj.length);
          if (obj.length == 0) {
            $("#assign_doc").css("display", "none");
            $("#assign_doc_err").html(
              "No doctors are found for this clinic...!"
            );
            $(".add_doc_btn1").css("display", "block");
          } else {
            $("#assign_doc").css("display", "block");
            $(".add_doc_btn1").css("display", "none");
            var option = $("<option />");
            option.attr("value", "0").text("Select Doctor");
            $("#assign_doc").append(option);
            $(obj).each(function () {
              // var option ='<option '
              option = $("<option />");
              option.attr("value", this.value).text(this.label);
              $("#assign_doc").append(option);
            });
            $("#assign_doc").val(doc_id);
            /*ends */
          }
        },
      });
      $("#assign_doctor").modal("show");
    }

    function conversation_status(id, status) {
      if (status == 1) {
        $(".app-modal-title").html(lg_accept);
        $("#app-modal-title").html(lg_accept);
        $("#appoinments_status").val("1");
        $("#appoinments_id").val(id);
      }

      if (status == 0) {
        $(".app-modal-title").html(lg_cancel);
        $("#app-modal-title").html(lg_cancel);
        $("#appoinments_status").val("0");
        $("#appoinments_id").val(id);
      }

      $("#appoinments_status_modal").modal("show");
    }
    function change_status() {
      var appoinments_id = $("#appoinments_id").val();
      var appoinments_status = $("#appoinments_status").val();
      $("#change_btn").attr("disabled", true);
      $("#change_btn").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      $.post(
        base_url + "appoinments/change_status",
        {
          appoinments_id: appoinments_id,
          appoinments_status,
          appoinments_status,
        },
        function (res) {
          my_appoinments(0);

          $("#change_btn").attr("disabled", false);
          $("#change_btn").html(lg_yes);
          $("#appoinments_status_modal").modal("hide");
        }
      );
    }
  }
}
if (modules == "patient") {
  if (pages == "appoinments") {
    setInterval(function () {
      // my_pappoinments(0);
      var loadvalue = $("#page_no_hidden").val();
      my_pappoinments(loadvalue);
    }, 1000);
    function my_pappoinments(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
      }

      var page = $("#page_no_hidden").val();

      //$('#search-error').html('');

      $.ajax({
        url: base_url + "appoinments/patient_appoinments_list",
        type: "POST",
        data: { page: page },
        beforeSend: function () {
          // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
        },
        success: function (response) {
          //$('#doctor-list').html('');
          if (response) {
            var obj = $.parseJSON(response);

            if (obj.current_page_no == 1) {
              $("#appointment-list").html(obj.data);
            } else {
              // $("#appointment-list").append(obj.data);
              $("#appointment-list").html(obj.data);
            }

            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 8) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      my_pappoinments(1);
    });

    function show_appoinments_modal(app_date, book_date, amount, type) {
      $(".app_date").html(app_date);
      $(".book_date").html(book_date);
      $(".amount").html(amount);
      $(".type").html(type);
      $("#appoinments_details").modal("show");
    }
  }
}

if (pages == "invoice") {
  $(document).ready(function () {
    //datatables
    var invoice_table;

    invoice_table = $("#invoice_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "invoice/invoice_list",
        type: "POST",
        data: function (data) {},
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0, 5], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });
  });
}

if (pages == "accounts") {
  if (modules == "doctor" || modules == "pharmacy" || modules == "lab") {
    //datatables
    var accounts_table;

    accounts_table = $("#accounts_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "accounts/doctor_accounts_list",
        type: "POST",
        data: function (data) {},
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0, 4], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    function account_table() {
      accounts_table.ajax.reload(null, false);
    }

    var patient_refund_request_table;

    patient_refund_request_table = $("#patient_refund_request").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "accounts/patient_refund_request",
        type: "POST",
        data: function (data) {},
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0], //first column / numbering column
          orderable: true, //set not orderable
        },
      ],
    });

    function patient_refund_request() {
      patient_refund_request_table.ajax.reload(null, false);
    }

    function send_request(id, status) {
      $.post(
        base_url + "accounts/send_request",
        { id: id, status: status },
        function (res) {
          account_table();
          patient_refund_request();
        }
      );
    }
  }

  if (modules == "patient") {
    //datatables
    var doctor_request_table;

    doctor_request_table = $("#doctor_request").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "accounts/doctor_request",
        type: "POST",
        data: function (data) {},
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0], //first column / numbering column
          orderable: true, //set not orderable
        },
      ],
    });

    function doctor_request() {
      doctor_request_table.ajax.reload(null, false);
    }

    //datatables
    var paccounts_table;

    paccounts_table = $("#accounts_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "accounts/patient_accounts_list",
        type: "POST",
        data: function (data) {},
        error: function () {},
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0, 5], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    function paccount_table() {
      paccounts_table.ajax.reload(null, false);
    }

    function send_request(id, status) {
      $.post(
        base_url + "accounts/send_request",
        { id: id, status: status },
        function (res) {
          paccount_table();
          doctor_request();
        }
      );
    }
  }

  function add_account_details() {
    $.ajax({
      url: base_url + "accounts/get_account_details",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        if (data) {
          $('[name="bank_name"]').val(data.bank_name);
          $('[name="branch_name"]').val(data.branch_name);
          $('[name="account_no"]').val(data.account_no);
          $('[name="account_name"]').val(data.account_name);
          $('[name="account_type"]').val(data.account_type);
          $('[name="account_currency"]').val(data.account_currency);
          $('[name="routing_number"]').val(data.routing_number);
          $('[name="ach_number"]').val(data.ach_number);
          $('[name="swift"]').val(data.swift);
          $('[name="bank_address"]').val(data.bank_address);

          $("#accounts_modal_title").text(lg_edit_details);
        } else {
          $("#accounts_form")[0].reset();
          $("#accounts_modal_title").text(lg_add_account_details);
        }

        // show bootstrap modal when complete loaded
      },
    });

    $("#account_modal").modal("show");
  }
  function add_insurance_details() {
    $.ajax({
      url: base_url + "accounts/get_insurance_details",
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        console.log(data);
        if (data) {
          $('[name="insurance_company"]').val(data.insurance_company);
          $('[name="insurance_card_number"]').val(data.insurance_card_number);
          $('[name="insurance_type"]').val(data.insurance_type);
          $('[name="insurance_expiration"]').val(data.insurance_expiration);
          $('[name="benefits"]').val(data.benefits);
          $('[name="phone_number"]').val(data.phone_number);
          $('[name="dependants"]').val(data.dependants);
          $('[name="dob"]').val(data.dob);

          $("#insurance_modal_title").text("Edit Insurance Details");
        } else {
          $("#insurance_form")[0].reset();
          $("#insurance_modal_title").text("Add Insurance Details");
        }

        // show bootstrap modal when complete loaded
      },
    });

    $("#insurance_modal").modal("show");
  }

  function payment_request(type) {
    $("#payment_type").val(type);
    $("#payment_request_modal").modal("show");
  }

  function amount() {
    var a = $("#request_amount").val();
    if (a != "") {
      $("#request_amount").val(parseFloat(a));
    }
  }

  $(".numonly").keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g, "");
  });

  $(".request_btn").click(function () {
    $("#request_amount").val("");
    $("#description").val("");
  });
  $(document).ready(function (e) {
    // Account details form
    $("#accounts_form")
      .submit(function (e) {
        e.preventDefault();
      })
      .validate({
        rules: {
          bank_name: {
            required: true,
            minlength: 1,
            maxlength: 150,
            text_spaces_only: true,
          },
          branch_name: {
            required: true,
            minlength: 1,
            maxlength: 150,
            text_spaces_only: true,
          },
          account_no: {
            required: true,
            minlength: 1,
            maxlength: 150,
            number: true,
          },
          account_name: {
            required: true,
            minlength: 1,
            maxlength: 150,
            text_spaces_only: true,
          },
        },
        messages: {
          bank_name: {
            required: lg_please_enter_ba,
          },
          branch_name: {
            required: lg_please_enter_br,
          },
          account_no: {
            required: lg_please_enter_ac1,
          },
          account_name: {
            required: lg_please_enter_ac2,
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "accounts/add_account_details",
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
              $("#acc_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
              $("#acc_btn").attr("disabled", true);
            },
            success: function (data) {
              $("#acc_btn").html(lg_save);
              $("#acc_btn").attr("disabled", false);

              var obj = jQuery.parseJSON(data);
              if (obj.result == "true") {
                toastr.success(obj.status);

                $("#account_modal").modal("hide");
                $("#bank_name").val(bank_name);
                $("#branch_name").val(branch_name);
                $("#account_no").val(account_no);
                $("#account_name").val(account_name);
                $("#btn-add-edit-title").html(lg_edit_details);
              } else {
                toastr.error(obj.status);
              }
            },
          });
          return false;
        },
      });

    $("#insurance_form")
      .submit(function (e) {
        e.preventDefault();
      })
      .validate({
        rules: {
          insurance_company_name: {
            required: true,
          },
          insurance_card_number: {
            required: true,
          },
          insurance_type: {
            required: true,
          },
          
          phone_number: {
            required: true,
          },
          
          dob: {
            required: true,
          },
        },
        messages: {
          insurance_company_name: {
            required: "Please enter Insurance Company Name",
          },
          insurance_card_number: {
            required: "Please enter Insurance Card Name",
          },
          insurance_type: {
            required: "Please enter Insurace Type",
          },
          
          phone_number: {
            required: "Please enter Your Phone Number",
          },
          
          dob: {
            required: "Please enter Dob",
          },
        },
        submitHandler: function (form) {
          $.ajax({
            url: base_url + "accounts/add_insurance_details",
            type: "POST",
            data: new FormData(form),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
              $("#acc_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
              $("#acc_btn").attr("disabled", true);
            },
            success: function (data) {
              $("#acc_btn").html(lg_save);
              $("#acc_btn").attr("disabled", false);

              var obj = jQuery.parseJSON(data);
              if (obj.result == "true") {
                toastr.success(obj.status);

                $("#insurance_modal").modal("hide");
                $("#bank_name").val(bank_name);
                $("#branch_name").val(branch_name);
                $("#account_no").val(account_no);
                $("#account_name").val(account_name);
                $("#btn-add-edit-title").html(lg_edit_details);
              } else {
                toastr.error(obj.status);
              }
            },
          });
          return false;
        },
      });
    // Account details form

    /*$("#payment_request_form").on('submit', (function (e) {
            e.preventDefault();


            var request_amount = $('[name="request_amount"]').val();

            if (request_amount == '') {
                toastr.error(lg_please_enter_am);
                return false;
            }

            $.ajax({
                url: base_url + 'accounts/payment_request',
                type: "POST",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {

                    $('#request_btn').html('<div class="spinner-border text-light" role="status"></div>');
                    $('#request_btn').attr('disabled', true);

                },
                success: function (data) {

                    $('#request_btn').html(lg_request1);
                    $('#request_btn').attr('disabled', false);

                    var obj = jQuery.parseJSON(data);
                    if (obj.result == 'true')
                    {

                        toastr.success(obj.status);

                        $('#payment_request_modal').modal('hide');
                        $('#payment_request_form')[0].reset();

                        window.location.reload();
                    } else
                    {
                        toastr.error(obj.status);
                    }
                }

            });
        }));*/

    /*submit form ajax template*/
    $("#payment_request_form")
      .submit(function (e) {
        e.preventDefault();
      })
      .validate({
        rules: {
          request_amount: {
            required: true,
            maxlength: 100,
            min: 1,
            number: true,
          },
          description: {
            // required: true,
            maxlength: 500,
            accept_chars: true,
          },
        },
        messages: {
          request_amount: {
            required: lg_form_lab_test_amount_req,
            maxlength: lg_form_lab_test_amount_max,
          },
          description: {
            // required: lg_form_lab_test_description_req,
            maxlength: lg_form_lab_test_description_max,
          },
        },
        submitHandler: function (form) {
          // form data
          var formData = new FormData($("#payment_request_form")[0]);

          // ajax
          $.ajax({
            url: base_url + "accounts/payment_request",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
              $("#request_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
              $("#request_btn").attr("disabled", true);
            },
            success: function (data) {
              $("#request_btn").html(lg_request1);
              $("#request_btn").attr("disabled", false);

              var obj = jQuery.parseJSON(data);
              if (obj.result == "true") {
                toastr.success(obj.status);

                $("#payment_request_modal").modal("hide");
                $("#payment_request_form")[0].reset();

                window.location.reload();
              } else {
                toastr.error(obj.status);
              }
            },
          });

          return false;
        },
      });
    /*submit form ajax template*/
  });
}

if (modules == "home" || modules == "ecommerce") {
  if (pages == "products_list_by_pharmacy" || pages == "index") {
    function getproduct_key() {
      var pr_key = $("#keywords").val();
      var subcategoryarray = $("input:checkbox:checked.subcategotyCheckbox")
        .map(function () {
          return this.value;
        })
        .get();

      var category = $("#category").val();
      var subcategory = $("#subcategory").val();
      //var keywords = $('#keywords').val();
      var pharmacy_id = $("#pharmacy_id").val();

      $.ajax({
        type: "POST",
        url: base_url + "Products/get_search_key_products",
        data: {
          keywords: pr_key,
          category: category,
          pharmacy_id: pharmacy_id,
          subcategory: subcategoryarray,
        },
        //dataType:'json',
        success: function (response) {
          arr = $.parseJSON(response);

          $("#keywords").autocomplete({
            source: arr,
          });
        },
      });
    }
  }

  if (pages == "doctors_searchmap") {
    google.maps.visualRefresh = true;
    var slider,
      infowindow = null;
    var bounds = new google.maps.LatLngBounds();
    var map,
      current = 0;

    var icons = {
      default: "assets/img/marker.png",
    };

    function show() {
      infowindow.close();
      if (!map.slide) {
        return;
      }
      var next, marker;
      if (locations.length == 0) {
        return;
      } else {
        next = 0;
      }

      current = next;
      marker = locations[next];
      setInfo(marker);
      // console.log(locations);
      infowindow.open(map, marker);
    }

    function initialize() {
      var bounds = new google.maps.LatLngBounds();
      var mapOptions = {
        zoom: 14,
        //center: new google.maps.LatLng(53.470692, -2.220328),
        scrollwheel: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
      };

      map = new google.maps.Map(document.getElementById("map"), mapOptions);
      map.slide = true;

      setMarkers(map, locations);
      infowindow = new google.maps.InfoWindow({
        content: "loading...",
      });

      google.maps.event.addListener(infowindow, "closeclick", function () {
        infowindow.close();
      });
      slider = window.setTimeout(show, 3000);
    }

    function setInfo(marker) {
      var content =
        '<div class="profile-widget" style="width: 100%; display: inline-block;">' +
        '<div class="doc-img">' +
        '<a href="' +
        marker.profile_link +
        '" tabindex="0" target="_blank">' +
        '<img class="img-fluid" alt="' +
        marker.doc_name +
        '" src="' +
        marker.image +
        '">' +
        "</a>" +
        "</div>" +
        '<div class="pro-content">' +
        '<h3 class="title">' +
        '<a href="' +
        marker.profile_link +
        '" tabindex="0">' +
        marker.doc_name +
        "</a>" +
        '<i class="fas fa-check-circle verified"></i>' +
        "</h3>" +
        '<p class="speciality">' +
        marker.speciality +
        "</p>" +
        '<div class="rating">' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<span class="d-inline-block average-rating"> (' +
        marker.total_review +
        ")</span>" +
        "</div>" +
        '<ul class="available-info">' +
        '<li><i class="fas fa-map-marker-alt"></i> ' +
        marker.address +
        " </li>" +
        //'<li><i class="far fa-clock"></i> ' + marker.next_available + '</li>'+
        '<li><i class="far fa-money-bill-alt"></i> ' +
        marker.amount +
        "</li>" +
        "</ul>" +
        "</div>" +
        "</div>";
      infowindow.setContent(content);
    }

    function setMarkers(map, markers) {
      for (var i = 0; i < markers.length; i++) {
        var item = markers[i];
        var latlng = new google.maps.LatLng(item.lat, item.lng);
        var marker = new google.maps.Marker({
          position: latlng,
          map: map,
          doc_name: item.doc_name,
          address: item.address,
          speciality: item.speciality,
          // next_available: item.next_available,
          amount: item.amount,
          profile_link: item.profile_link,
          total_review: item.total_review,
          animation: google.maps.Animation.DROP,
          icon: icons[item.icons],
          image: item.image,
        });
        bounds.extend(marker.position);
        markers[i] = marker;
        google.maps.event.addListener(marker, "click", function () {
          setInfo(this);
          infowindow.open(map, this);
          window.clearTimeout(slider);
        });
      }
      map.fitBounds(bounds);
      google.maps.event.addListener(map, "zoom_changed", function () {
        if (map.zoom > 16) map.slide = false;
      });
    }

    //google.maps.event.addDomListener(window, 'load', initialize);
  }
}

if (pages == "products_list_by_pharmacy" || modules == "ecommerce") {
  function get_products(load_more) {
    var subcategoryarray = $("input:checkbox:checked.subcategotyCheckbox")
      .map(function () {
        return this.value;
      })
      .get();

    //console.log(values)
    if (load_more == 0) {
      $("#page_no_hidden").val(1);
    }

    var page = $("#page_no_hidden").val();
    var category = $("#category").val();
    var subcategory = $("#subcategory").val();
    var keywords = $("#keywords").val();
    var pharmacy_id = $("#pharmacy_id").val();

    //$('#search-error').html('');

    $.ajax({
      url: base_url + "home/get_products",
      type: "POST",
      data: {
        page: page,
        category: category,
        subcategory: subcategoryarray,
        keywords: keywords,
        pharmacy_id: pharmacy_id,
        subcategoryarray: subcategoryarray,
      },
      beforeSend: function () {
        $("#loading").show();
      },
      complete: function () {
        $("#loading").hide();
      },
      success: function (response) {
        if (response) {
          var obj = $.parseJSON(response);
          if (obj.data.length >= 1) {
            var html = "";

            $(obj.data).each(function () {
              $(".categoty_title_name").html(obj.category_name);
              html +=
                '<div class="col-md-12 col-lg-3 col-xl-3 product-custom">';
              html += '<div class="profile-widget">';
              html += '<div class="doc-img">';
              html +=
                '<a href="' +
                base_url +
                "product-details/" +
                this.slug +
                '" tabindex="-1">';
              html +=
                '<img class="img-fluid" alt="Product image" src="' +
                this.product_image +
                '">';
              html += "</a>";
              // html += '<a href="javascript:void(0)" class="fav-btn" tabindex="-1">';
              // html += '<i class="far fa-bookmark"></i>';
              // html += '</a>';
              html += "</div>";
              html += '<div class="pro-content">';
              html += '<h3 class="title pb-4">';
              html +=
                '<a href="' +
                base_url +
                "product-details/" +
                this.slug +
                '" tabindex="-1">' +
                this.name +
                "</a>";
              html += "</h3>";
              html += '<div class="row align-items-center">';
              html += '<div class="col-lg-6">';
              if (this.price != this.sale_price) {
                html +=
                  '<span class="price">' +
                  this.user_currency_sign +
                  " " +
                  this.price +
                  "</span>";
                html +=
                  '<span class="price-strike">  ' +
                  this.user_currency_sign +
                  "  " +
                  this.sale_price +
                  "</span>";
              } else {
                html +=
                  '<span class="price">' +
                  this.user_currency_sign +
                  " " +
                  this.price +
                  "</span>";
              }
              html += "</div>";
              html += '<div class="col-lg-6 text-right">';
              html +=
                '<a href="javascript:void(0);" onclick="add_cart(\'' +
                this.productid +
                '\')" class="cart-icon"><i class="fas fa-shopping-cart"></i></a>';
              html += "</div>";
              html += "</div>";
              html += "</div>";
              html += "</div>";
              html += "</div>";
            });

            if (obj.current_page_no == 1) {
              $("#product-list").html(html);
            } else {
              $("#product-list").append(html);
            }
          } else {
            var html =
              '<div class="col-md-12">' +
              '<div class="health-item-product1 text-center">' +
              '<div class="health-img-popular1">' +
              '<div class="card">' +
              '<div class="card-body">' +
              '<p class="mb-0">' +
              lg_no_products_fou +
              "</p>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>" +
              "</div>";

            $("#product-list").html(html);
          }

          var minimized_elements = $("h4.minimize");
          minimized_elements.each(function () {
            var t = $(this).text();
            if (t.length < 100) return;
            $(this).html(
              t.slice(0, 100) +
                '<span>... </span><a href="#" class="more">' +
                lg_more +
                "</a>" +
                '<span style="display:none;">' +
                t.slice(100, t.length) +
                ' <a href="#" class="less">' +
                lg_less +
                "</a></span>"
            );
          });

          $(".search-results").html(
            "<span>" + obj.count + " " + lg_matches_for_you + "</span>"
          );
          if (obj.count == 0) {
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
            return false;
          }

          if (obj.current_page_no == 1 && obj.count < 5) {
            $("page_no_hidden").val(1);
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
            return false;
          }

          if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
            $("#load_more_btn").removeClass("d-none");
            $("#no_more").addClass("d-none");
          } else {
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
          }
        }
      },
    });
  }

  function add_cart(product_id) {
    var cart_qty = $("#cart_qty").val();

    $.ajax({
      url: base_url + "cart/add_cart",
      type: "POST",
      data: { product_id: product_id, cart_qty: cart_qty },
      cache: false,
      beforeSend: function () {},
      success: function (data) {
        //console.log(data)
        var obj = jQuery.parseJSON(data);
        if (obj.result == "true") {
          toastr.success(obj.msg);
          $(".cart_count").html(obj.cart_count);
        } else {
          toastr.error(obj.msg);
          $(".cart_count").html(obj.cart_count);
        }
      },
    });
  }

  function increaseValue() {
    var value = parseInt(document.getElementById("cart_qty").value, 10);
    value = isNaN(value) ? 1 : value;
    value++;
    document.getElementById("cart_qty").value = value;
  }

  function decreaseValue() {
    var value = parseInt(document.getElementById("cart_qty").value, 10);
    value = isNaN(value) ? 1 : value;
    value < 1 ? (value = 1) : "";
    value--;
    document.getElementById("cart_qty").value = value;
  }

  function increment_quantity(cart_id) {
    var inputQuantityElement = $("#input-quantity-" + cart_id);
    var newQuantity = parseInt($(inputQuantityElement).val()) + 1;
    save_to_db(cart_id, newQuantity);
  }

  function decrement_quantity(cart_id) {
    var inputQuantityElement = $("#input-quantity-" + cart_id);
    if ($(inputQuantityElement).val() > 1) {
      var newQuantity = parseInt($(inputQuantityElement).val()) - 1;
      save_to_db(cart_id, newQuantity);
    }
  }

  function remove_cart(id) {
    $.ajax({
      url: base_url + "cart/remove_cart",
      type: "POST",
      data: { id: id },
      cache: false,
      beforeSend: function () {
        $("#loading").show();
      },
      success: function (data) {
        cart_count();
        cart_lists();
      },
    });
  }

  function save_to_db(cart_id, new_quantity) {
    var inputQuantityElement = $("#input-quantity-" + cart_id);
    $.ajax({
      url: base_url + "cart/update_cart",
      data: "cart_id=" + cart_id + "&new_quantity=" + new_quantity,
      type: "post",
      beforeSend: function () {
        $("#loading").show();
      },

      success: function (response) {
        cart_count();
        cart_lists();
      },
    });
  }

  function cart_count() {
    $.get(base_url + "cart/cart_count", function (data) {
      var obj = jQuery.parseJSON(data);
      $(".cart_count").html(obj.cart_count);
    });
  }

  function cart_lists() {
    $.get(base_url + "cart/cart_lists", function (data) {
      $("#loading").hide();
      var obj = jQuery.parseJSON(data);
      $(".cart_lists").html(obj.cart_list);
      $(".checkout_cart_lists").html(obj.checkout_html);
      $(".checkout_cart_html").html(obj.checkout_cart_html);
      $("#cart_pay_btn").hide();
      if (obj.cart_count == 1) {
        $("#cart_pay_btn").show();
      }
    });
  }
}

if (modules == "home") {
  if (pages == "doctors_mapsearch") {
    $(document).ready(function () {
      $("#services").multiselect({
        nonSelectedText: lg_select_services,
        enableClickableOptGroups: true,
        enableCollapsibleOptGroups: true,
        enableFiltering: true,
        includeSelectAllOption: true,
        includeResetOption: true,
      });

      $("#gender").multiselect({
        nonSelectedText: lg_select_gender,
        enableClickableOptGroups: true,
        enableCollapsibleOptGroups: true,
        enableFiltering: true,
        includeSelectAllOption: true,
        includeResetOption: true,
      });
    });
    var locations = [];
    google.maps.visualRefresh = true;
    var slider,
      infowindow = null;
    var bounds = new google.maps.LatLngBounds();
    var map,
      current = 0;

    var icons = {
      default: "assets/img/marker.png",
    };

    function show() {
      infowindow.close();
      if (!map.slide) {
        return;
      }
      var next, marker;
      if (locations.length == 0) {
        return;
      } else {
        next = 0;
      }

      current = next;
      marker = locations[next];
      setInfo(marker);
      // console.log(locations);
      infowindow.open(map, marker);
    }

    function reset_doctor() {
      $("#orderby").val("");
      $("#keywords").val("");
      $("#appointment_type").val("");
      $("#gender").val("");
      $("#appointment_type").val("");
      $("#specialization").val("");
      $("#lang").val("");
      $("#ethnicity").val("");
      $("#country").val("");
      $("#administrative_area_level_1").val("");
      $("#postal_code").val("");
      $("#locality").val("");
      $("#search_location").val("");
      $("#search_keywords").val("");
      $("#search_radius").val("");
      $("#s_unit").val("");
      $("#s_lat").val("");
      $("#s_long").val("");
      // $('#search_doctor_form')[0].reset();
      search_doctor(0);
      location.reload(true);
    }

    search_doctor(0);

    function search_doctor(load_more) {
      if (load_more == 0) {
        $("#page_no_hidden").val(1);
        locations = [];
      }

      var specialization_list = $("#services").val();
      var specialization = "";
      var len1 = length.specialization_list;
      var last_index1 = len1 - 1;

      $.each(specialization_list, function (i, val) {
        specialization += val;

        if (i === last_index1) {
          specialization += "";
        } else {
          specialization += ",";
        }
      });

      var gender_list = $("#gender").val();

      var gender = "";
      var len2 = length.gender_list;
      var last_index = len2 - 1;
      $.each(gender_list, function (i, val) {
        gender += val;

        if (i === last_index) {
          gender += "";
        } else {
          gender += ",";
        }
      });

      var lang = $("#lang").val();
      var ethnicity = $("#ethnicity").val();
      var order_by = $("#orderby").val();
      var page = $("#page_no_hidden").val();

      var appointment_type = $("#appointment_type").val();
      //var city = $("#city").val();
      //var state = $("#state").val();
      //var country = $("#country").val();
      var keywords = $("#search_keywords").val();
      var s_country = $("#country").val();
      var s_state = $("#administrative_area_level_1").val();
      var s_locality = $("#locality").val();
      var s_postal_code = $("#postal_code").val();
      var s_lat = $("#s_lat").val();
      var s_long = $("#s_long").val();
      var role = $("#role").val();
      var search_radius = $("#search_radius").val();
      var s_unit = $("#s_unit").val();
      var services = $("#services").val();
      var sub_services = $("#sub_services").val();
      var s_location = $("#search_location").val();
      //$('#search-error').html('');

      $.ajax({
        url: base_url + "home/search_doctor",
        type: "POST",
        data: {
          appointment_type: appointment_type,
          gender: gender,
          specialization: specialization,
          lang: lang,
          ethnicity: ethnicity,
          order_by: order_by,
          page: page,
          role: role,
          keywords: keywords,
          postal_code: s_postal_code,
          s_lat: s_lat,
          s_long: s_long,
          s_radius: search_radius,
          s_unit: s_unit,
          s_location: s_location,
          city: s_locality,
          //citys: citys,
          state: s_state,
          country: s_country,
          services: services,
          sub_services: sub_services,
        },
        beforeSend: function () {
          $("#doctor-search").attr("disabled", true);
          $("#doctor-search").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
          // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
        },
        success: function (response) {
          $("#doctor-search").attr("disabled", false);
          $("#doctor-search").html(lg_search3);
          if (response) {
            var obj = $.parseJSON(response);

            if (obj.data.length >= 1) {
              var html = "";
              $(obj.data).each(function () {
                var services = "";
                var view_more = "";
                //var service_latest = '';
                if (this.services != null && this.services.length != 0) {
                  var service = this.services.split(",");
                  for (var i = 0; i < service.length; i++) {
                    services += "<span>" + service[i] + "</span>";

                    if (i == 2) {
                      view_more =
                        '<a href="' +
                        base_url +
                        "doctor-preview/" +
                        this.username +
                        '">' +
                        lg_view_more +
                        "</a>";
                      break;
                    }
                  }
                }
                var clinic_images = "";

                var clinic_images_file = $.parseJSON(this.clinic_images);
                $.each(clinic_images_file, function (key, item) {
                  var userid = item.user_id;
                  clinic_images +=
                    '<li> <a href="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" data-fancybox="gallery"> <img src="uploads/clinic_uploads/' +
                    userid +
                    "/" +
                    item.clinic_image +
                    '" alt="Feature"> </a> </li>';
                });
                html +=
                  '<div class="card">' +
                  '<div class="card-body">' +
                  '<div class="doctor-widget">' +
                  '<div class="doc-info-left">' +
                  '<div class="doctor-img">' +
                  '<a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  '<img src="' +
                  this.profileimage +
                  '" class="img-fluid" alt="User Image">' +
                  "</a>" +
                  "</div>" +
                  '<div class="doc-info-cont">' +
                  '<h4 class="doc-name"><a href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  lg_dr +
                  " " +
                  this.first_name +
                  " " +
                  this.last_name +
                  "</a></h4>" +
                  '<h5 class="doc-department">' +
                  this.speciality +
                  "</h5>" +
                  '<div class="rating">';
                for (var j = 1; j <= 5; j++) {
                  if (j <= this.rating_value) {
                    html += '<i class="fas fa-star filled"></i>';
                  } else {
                    html += '<i class="fas fa-star"></i>';
                  }
                }
                html +=
                  '<span class="d-inline-block average-rating">(' +
                  this.rating_count +
                  ")</span>" +
                  "</div>" +
                  '<div class="clinic-details">' +
                  '<p class="doc-location"><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</p>" +
                  ' <ul class="clinic-gallery">' +
                  clinic_images +
                  "</ul>" +
                  "</div>" +
                  '<div class="Tags">' +
                  '<div class="clinic-services">' +
                  services +
                  "</div>" +
                  "</div>" +
                  view_more +
                  "</div>" +
                  "</div>" +
                  '<div class="doc-info-right">' +
                  '<div class="clini-infos">' +
                  "<ul>" +
                  '<li><i class="far fa-comment"></i>' +
                  this.rating_count +
                  " " +
                  lg_feedback +
                  "</li>" +
                  '<li><i class="fas fa-map-marker-alt"></i> ' +
                  this.cityname +
                  ", " +
                  this.countryname +
                  "</li>" +
                  '<li><i class="far fa-money-bill-alt"></i> ' +
                  this.amount +
                  " </li>";

                html +=
                  "</ul>" +
                  "</div>" +
                  '<div class="clinic-booking">' +
                  '<a class="view-pro-btn" href="' +
                  base_url +
                  "doctor-preview/" +
                  this.username +
                  '">' +
                  lg_view_profile +
                  "</a>" +
                  '<a class="apt-btn" href="' +
                  base_url +
                  "book-appoinments/" +
                  this.username +
                  '">' +
                  lg_book_appointmen +
                  "</a>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>" +
                  "</div>";

                location_items = {};
                location_items["id"] = this.id;
                location_items["doc_name"] =
                  lg_dr + " " + this.first_name + " " + this.last_name;
                location_items["speciality"] = services;
                location_items["address"] =
                  this.cityname + ", " + this.countryname;
                location_items["next_available"] = "Available on Fri, 22 Mar";
                location_items["amount"] = this.amount;
                location_items["lat"] = this.latitude;
                location_items["lng"] = this.longitude;
                location_items["icons"] = "default";
                location_items["profile_link"] =
                  base_url + "doctor-preview/" + this.username;
                location_items["total_review"] =
                  this.rating_count + " " + lg_feedback;
                location_items["image"] = this.profileimage;

                locations.push(location_items);
              });

              //console.log(lat_long);
              if (obj.current_page_no == 1) {
                $("#doctor-list").html(html);
                initialize(obj.least_lat, obj.most_long);
              } else {
                $("#doctor-list").append(html);
                setMarkers(map, locations);
              }
            } else {
              location_items = {};
              locations.push(location_items);
              $("#map").hide();
              var html =
                '<div class="card">' +
                '<div class="card-body">' +
                '<div class="doctor-widget">' +
                "<p>" +
                lg_no_doctors_foun +
                "</p>" +
                "</div>" +
                "</div>" +
                "</div>";
              $("#doctor-list").html(html);
            }

            var minimized_elements = $("h4.minimize");
            minimized_elements.each(function () {
              var t = $(this).text();
              if (t.length < 100) return;
              $(this).html(
                t.slice(0, 100) +
                  '<span>... </span><a href="#" class="more">' +
                  lg_more +
                  "</a>" +
                  '<span style="display:none;">' +
                  t.slice(100, t.length) +
                  ' <a href="#" class="less">' +
                  lg_less +
                  "</a></span>"
              );
            });
            // $('a.more', minimized_elements).click(function(event) {
            //   event.preventDefault();
            //   $(this).hide().prev().hide();
            //   $(this).next().show();
            // });

            // $('a.less', minimized_elements).click(function(event) {
            //   event.preventDefault();
            //   $(this).parent().hide().prev().show().prev().show();
            // });

            $(".search-results").html(
              "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
            );
            // $(".widget-title").html(obj.count+' Matches for your search');
            if (obj.count == 0) {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.current_page_no == 1 && obj.count < 5) {
              $("page_no_hidden").val(1);
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
              return false;
            }

            if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
              $("#load_more_btn").removeClass("d-none");
              $("#no_more").addClass("d-none");
            } else {
              $("#load_more_btn").addClass("d-none");
              $("#no_more").removeClass("d-none");
            }
          }
        },
      });
    }
    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_doctor(1);
    });

    function initialize(lat, long) {
      $("#map").show();
      var bounds = new google.maps.LatLngBounds();
      var mapOptions = {
        zoom: 8,
        // center: new google.maps.LatLng(25.7616798, -80.1917902),
        center: new google.maps.LatLng(lat, long),
        //mapTypeControl: true,
        // mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
        navigationControl: true,
        mapTypeId: google.maps.MapTypeId.ROADMAP,

        styles: [
          {
            elementType: "geometry",
            stylers: [
              {
                color: "#232323",
              },
            ],
          },
          {
            elementType: "labels.text.stroke",
            stylers: [
              {
                color: "#232323",
              },
            ],
          },
          {
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#746855",
              },
            ],
          },
          {
            featureType: "administrative.locality",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#669933",
              },
            ],
          },
          {
            featureType: "poi",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#669933",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "geometry",
            stylers: [
              {
                color: "#263c3f",
              },
            ],
          },
          {
            featureType: "poi.park",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#6b9a76",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "geometry",
            stylers: [
              {
                color: "#38414e",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "geometry.stroke",
            stylers: [
              {
                color: "#212a37",
              },
            ],
          },
          {
            featureType: "road",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#9ca5b3",
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "geometry",
            stylers: [
              {
                color: "#746855",
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "geometry.stroke",
            stylers: [
              {
                color: "#1f2835",
              },
            ],
          },
          {
            featureType: "road.highway",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#f3d19c",
              },
            ],
          },
          {
            featureType: "transit",
            elementType: "geometry",
            stylers: [
              {
                color: "#2f3948",
              },
            ],
          },
          {
            featureType: "transit.station",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#d59563",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "geometry",
            stylers: [
              {
                color: "#151515",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "labels.text.fill",
            stylers: [
              {
                color: "#515c6d",
              },
            ],
          },
          {
            featureType: "water",
            elementType: "labels.text.stroke",
            stylers: [
              {
                color: "#333333",
              },
            ],
          },
        ],
      };

      map = new google.maps.Map(document.getElementById("map"), mapOptions);
      map.slide = true;

      setMarkers(map, locations);
      infowindow = new google.maps.InfoWindow({
        content: "loading...",
      });

      google.maps.event.addListener(infowindow, "closeclick", function () {
        infowindow.close();
      });

      slider = window.setTimeout(show, 3000);
    }

    function setInfo(marker) {
      var content =
        '<div class="profile-widget" style="width: 100%; display: inline-block;">' +
        '<div class="doc-img">' +
        '<a href="' +
        marker.profile_link +
        '" tabindex="0" target="_blank">' +
        '<img class="img-fluid" alt="' +
        marker.doc_name +
        '" src="' +
        marker.image +
        '">' +
        "</a>" +
        "</div>" +
        '<div class="pro-content">' +
        '<h3 class="title">' +
        '<a href="' +
        marker.profile_link +
        '" tabindex="0">' +
        marker.doc_name +
        "</a>" +
        '<i class="fas fa-check-circle verified"></i>' +
        "</h3>" +
        '<p class="speciality">' +
        marker.speciality +
        "</p>" +
        '<div class="rating">' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<i class="fas fa-star"></i>' +
        '<span class="d-inline-block average-rating"> (' +
        marker.total_review +
        ")</span>" +
        "</div>" +
        '<ul class="available-info">' +
        '<li><i class="fas fa-map-marker-alt"></i> ' +
        marker.address +
        " </li>" +
        //'<li><i class="far fa-clock"></i> ' + marker.next_available + '</li>'+
        '<li><i class="far fa-money-bill-alt"></i> ' +
        marker.amount +
        "</li>" +
        "</ul>" +
        "</div>" +
        "</div>";
      infowindow.setContent(content);
    }

    function setMarkers(map, markers) {
      for (var i = 0; i < markers.length; i++) {
        var item = markers[i];
        var latlng = new google.maps.LatLng(item.lat, item.lng);
        var marker = new google.maps.Marker({
          position: latlng,
          map: map,
          doc_name: item.doc_name,
          address: item.address,
          speciality: item.speciality,
          // next_available: item.next_available,
          amount: item.amount,
          profile_link: item.profile_link,
          total_review: item.total_review,
          animation: google.maps.Animation.DROP,
          icon: icons[item.icons],
          image: item.image,
        });
        bounds.extend(marker.position);
        markers[i] = marker;
        google.maps.event.addListener(marker, "click", function () {
          setInfo(this);
          infowindow.open(map, this);
          window.clearTimeout(slider);
        });
      }
      map.fitBounds(bounds);
      google.maps.event.addListener(map, "zoom_changed", function () {
        if (map.zoom > 16) map.slide = false;
      });
    }
  }

  if (modules == "home" && pages == "pharmacy_search_bydoctor") {
    //search_doctor(0);

    search_pharmacy(0);

    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_pharmacy(1);
    });
  }
}

function reset_pharmacy() {
  $("#orderby").val("");
  $("#search_pharmacy_form")[0].reset();
  search_pharmacy(0);
}

function search_pharmacy(load_more) {
  if (load_more == 0) {
    $("#page_no_hidden").val(1);
  }
  var order_by = $("#orderby").val();
  var page = $("#page_no_hidden").val();
  var city = $("#city").val();
  var state = $("#state").val();
  var country = $("#country").val();

  // Service filter
  var hrsopen = $("#24hrsopen").prop("checked") ? "yes" : "";
  var home_delivery = $("#home_delivery").prop("checked") ? "yes" : "";

  $.ajax({
    url: base_url + "home/search_pharmacy",
    type: "POST",
    data: {
      order_by: order_by,
      page: page,
      city: city,
      state: state,
      country: country,
      hrsopen: hrsopen,
      home_delivery: home_delivery,
    },
    beforeSend: function () {
      $("#loading").show();
    },
    complete: function () {
      $("#loading").hide();
    },
    success: function (response) {
      if (response) {
        var obj = $.parseJSON(response);
        if (obj.data.length >= 1) {
          var html = "";
          var no = 1;
          $(obj.data).each(function () {
            var pharmacy_name =
              this.pharmacy_name != "" && this.pharmacy_name != null
                ? this.pharmacy_name
                : "";
            var profileimage =
              this.profileimage != "" && this.profileimage != null
                ? this.profileimage
                : "";
            var phonecode =
              this.phonecode != "" && this.phonecode != null
                ? this.phonecode
                : "";
            var mobileno =
              this.mobileno != "" && this.mobileno != null ? this.mobileno : "";
            var address1 =
              this.address1 != "" && this.address1 != null ? this.address1 : "";
            var address2 =
              this.address2 != "" && this.address2 != null ? this.address2 : "";
            var city = this.city != "" && this.city != null ? this.city : "";
            var statename =
              this.statename != "" && this.statename != null
                ? this.statename
                : "";
            var country =
              this.country != "" && this.country != null ? this.country : "";
            var pharmacy_opens_at =
              this.pharmacy_opens_at != "" && this.pharmacy_opens_at != null
                ? this.pharmacy_opens_at
                : "";

            html += '<div class="card">';
            html += '<div class="card-body">';
            html += '<div class="doctor-widget">';
            html += '<div class="doc-info-left">';
            html += '<div class="doctor-img1">';
            html +=
              '<a href="' +
              base_url +
              "home/pharmacy_preview/" +
              btoa(this.id) +
              '">';
            html +=
              '<img src="' +
              profileimage +
              '" class="img-fluid" alt="' +
              pharmacy_name +
              '">';
            html += "</a>";
            html += "</div>";
            html += '<div class="doc-info-cont">';
            html +=
              '<h4 class="doc-name mb-2"><a  href="' +
              base_url +
              "home/view_pharmacy_products/" +
              btoa(this.id) +
              '" view_pharmacy_products(' +
              this.id +
              ")>" +
              pharmacy_name +
              "</a></h4>";
            html += '<div class="clinic-details">';
            html += '<div class="clini-infos pt-3">';

            html +=
              '<p class="doc-location mb-2"><i class="fas fa-phone-volume mr-1"></i> (+' +
              phonecode +
              ") - " +
              mobileno +
              "</p>";
            html +=
              '<p class="doc-location mb-2 text-ellipse"><i class="fas fa-map-marker-alt mr-1"></i> ' +
              address1 +
              " </p>";
            html +=
              '<p class="doc-location mb-2"><i class="fas fa-chevron-right mr-1"></i> ' +
              address2 +
              "</p>";

            html +=
              '<p class="doc-location mb-2"><i class="fas fa-chevron-right mr-1"></i> ' +
              city +
              " " +
              statename +
              " " +
              country +
              "</p>";

            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += '<div class="doc-info-right">';
            html += '<div class="clinic-booking">';
            html +=
              '<a class="view-pro-btn" href="' +
              base_url +
              "home/pharmacy_preview/" +
              btoa(this.id) +
              '">' +
              lg_view_details +
              "</a>";
            html +=
              '<a class="apt-btn" href="' +
              base_url +
              "home/view_pharmacy_products/" +
              btoa(this.id) +
              '" view_pharmacy_products(' +
              this.id +
              ")>" +
              lg_browse_products +
              "</a>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += "</div>";
            html += "</div>";

            no++;
          });

          if (obj.current_page_no == 1) {
            $("#pharmacy-list").html(html);
          } else {
            $("#pharmacy-list").append(html);
          }
        } else {
          var html =
            '<div class="card" style="width:100%">' +
            '<div class="card-body">' +
            '<div class="doctor-widget">' +
            "<p>" +
            lg_no_pharmacy_fou +
            "</p>" +
            "</div>" +
            "</div>" +
            "</div>";
          $("#pharmacy-list").html(html);
        }
        var minimized_elements = $("h4.minimize");
        minimized_elements.each(function () {
          var t = $(this).text();
          if (t.length < 100) return;
          $(this).html(
            t.slice(0, 100) +
              '<span>... </span><a href="#" class="more">' +
              lg_more +
              "</a>" +
              '<span style="display:none;">' +
              t.slice(100, t.length) +
              ' <a href="#" class="less">' +
              lg_less +
              "</a></span>"
          );
        });

        $(".search-results").html(
          "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
        );
        // $(".widget-title").html(obj.count+' Matches for your search');
        if (obj.count == 0) {
          $("#load_more_btn").addClass("d-none");
          $("#no_more").removeClass("d-none");
          return false;
        }

        if (obj.current_page_no == 1 && obj.count < 8) {
          $("page_no_hidden").val(1);
          $("#load_more_btn").addClass("d-none");
          $("#no_more").removeClass("d-none");
          return false;
        }

        if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
          $("#load_more_btn").removeClass("d-none");
          $("#no_more").addClass("d-none");
        } else {
          $("#load_more_btn").addClass("d-none");
          $("#no_more").removeClass("d-none");
        }
      }
    },
  });
}

if (modules == "pharmacy") {
  if (pages == "orderlist" || pages == "pharmacy_dashboard") {
    var products_table;
    //datatables
    products_table = $("#orders_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "pharmacy/orders_listDatatable",
        type: "POST",
        data: function (data) {},
        error: function () {
          //window.location.href=base_url+'admin/dashboard';
        },
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    $(document).on("change", ".order_status", function () {
      var id = $(this).attr("id");
      var status = $(this).val();
      $.post(
        base_url + "pharmacy/change_order_status",
        { id: id, status: status },
        function (data) {
          toastr.success(lg_status_updated_);
          products_table.ajax.reload(null, false);
        }
      );
    });
  }

  if (pages == "product_list") {
    var products_table;
    //datatables
    products_table = $("#products_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "pharmacy/products_list",
        type: "POST",
        data: function (data) {},
        error: function () {
          //window.location.href=base_url+'admin/dashboard';
        },
      },

      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });

    $(document).on("change", ".product_status", function () {
      var id = $(this).attr("id");
      var stat = $("#" + id).prop("checked");
      if (stat == true) {
        var status = 1;
      } else {
        var status = 2;
      }
      $.post(
        base_url + "products/change_status",
        { id: id, status: status },
        function (data) {
          toastr.success(lg_status_updated_);
          products_table.ajax.reload(null, false); //reload datatable ajax
        }
      );
    });

    $(document).on("click", ".product_delete", function () {
      var id = $(this).attr("id");
      var avoid = "delete";
      id = id.replace(avoid, "");
      if (confirm(lg_are_you_sure_de3)) {
        $.post(
          base_url + "products/product_delete",
          { id: id },
          function (data) {
            toastr.success(lg_product_deleted);
            products_table.ajax.reload(null, false); //reload datatable ajax
          }
        );
      }
    });

    function products_reload_table() {
      products_table.ajax.reload(null, false); //reload datatable ajax
    }

    function product_status(id) {
      var stat = $("#status_" + id).prop("checked");

      if (stat == true) {
        var status = 1;
      } else {
        var status = 2;
      }
      $.post(
        base_url + "products/change_status",
        { id: id, status: status },
        function (data) {
          products_reload_table();
        }
      );
    }

    function delete_products(id) {
      if (confirm(lg_are_you_sure_de3)) {
        // ajax delete data to database
        $.ajax({
          url: base_url + "admin/product/product_delete/" + id,
          type: "POST",
          dataType: "JSON",
          success: function (data) {
            //if success reload ajax table
            products_reload_table();
            toastr.success(lg_product_deleted);
          },
          error: function () {
            //window.location.href=base_url+'admin/dashboard';
          },
        });
      }
    }
  }

  if (pages == "add_product" || pages == "edit_product") {
    function number(field) {
      var regex = /\d*\.?\d?/g;
      field.value = regex.exec(field.value);
    }

    function isNumber(evt) {
      evt = evt ? evt : window.event;
      var charCode = evt.which ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
      }
      return true;
    }

    $(document).ready(function () {
      $("#upload_image_btn").click(function () {
        $("#avatar-image-modal").css("display", "block");
        $("#avatar-image-modal").modal("show");
      });

      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_product_category",
        data: { id: $(this).val() },
        beforeSend: function () {
          $("#category").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#category").find("option:eq(0)").html(lg_select_category);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#category").append(option);
          });
          $("#category").val(category);
        },
      });

      $.ajax({
        type: "GET",
        url: base_url + "ajax/get_unit",
        data: { id: $(this).val() },
        beforeSend: function () {
          $("#unit").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#unit").find("option:eq(0)").html(lg_select_unit);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#unit").append(option);
          });
          $("#unit").val(unit);
        },
      });

      $.ajax({
        type: "POST",
        url: base_url + "ajax/get_product_subcategory",
        data: { id: category },
        beforeSend: function () {
          $("#subcategory option:gt(0)").remove();
          $("#subcategory").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
          /*get response as json */
          $("#subcategory").find("option:eq(0)").html(lg_select_subcateg);
          var obj = jQuery.parseJSON(data);
          $(obj).each(function () {
            var option = $("<option />");
            option.attr("value", this.value).text(this.label);
            $("#subcategory").append(option);
          });

          $("#subcategory").val(subcategory);
        },
      });

      $("#category").change(function () {
        $.ajax({
          type: "POST",
          url: base_url + "ajax/get_product_subcategory",
          data: { id: $(this).val() },
          beforeSend: function () {
            $("#subcategory option:gt(0)").remove();
            $("#subcategory").find("option:eq(0)").html(lg_please_wait);
          },
          success: function (data) {
            /*get response as json */
            $("#subcategory").find("option:eq(0)").html(lg_select_subcateg);
            var obj = jQuery.parseJSON(data);
            $(obj).each(function () {
              var option = $("<option />");
              option.attr("value", this.value).text(this.label);
              $("#subcategory").append(option);
            });

            /*ends */
          },
        });
      });
      // $("#add_product").click(function(){
      //     $('#image-errors').show();
      // });

      $("#add_product").validate({
        rules: {
          name: {
            required: true,
            SpecCharValidate: true,
            remote: {
              url: base_url + "pharmacy/check_product_exists",
              type: "post",
              data: {
                name: function () {
                  return $("#name").val();
                },
              },
            },
          },
          category: "required",
          subcategory: "required",
          unit_value: "required",
          unit: "required",
          price: "required",
          sale_price: "required",
          description: { required: true, maxlength: 1000 },
          manufactured_by: { required: true, maxlength: 50 },
          short_description: { required: true, maxlength: 500 },
          upload_image_url: {
            required: true,
            accept: "image/jpg,image/jpeg,image/png,image/gif",
          },

          // alert ("This is an alert dialog box");
        },
        messages: {
          name: {
            required: lg_please_enter_pr,
            SpecCharValidate: "No Special Chars or Numbers Allowed",
            remote: "Product name already exists!",
          },
          category: lg_please_select_p1,
          subcategory: lg_please_select_p2,
          unit_value: lg_please_enter_un,
          unit: lg_please_select_u,
          price: lg_please_enter_pr1,
          sale_price: lg_please_enter_se1,
          description: {
            required: lg_please_enter_de1,
            maxlength: "Maximum description length should be 1000",
          },
          manufactured_by: {
            required: lg_please_enter_th,
            maxlength: "Maximum description length should be 50",
          },
          short_description: {
            required: lg_please_enter_th1,
            maxlength: "Maximum short description length should be 500",
          },
          upload_image_url: {
            required: "Required!",
            accept: "Invalid extension!",
          },
        },

        submitHandler: function (form) {
          if ($("#upload_image_url").val() == "") {
            $("#image-error").show();
            $("#image-error").html(lg_please_upload_p);
            return false;
          }

          $.ajax({
            url: base_url + "pharmacy/create_product",
            data: $("#add_product").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#product_btn").attr("disabled", true);
              $("#product_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#product_btn").attr("disabled", false);
              $("#product_btn").html(lg_add10);
              var obj = JSON.parse(res);

              if (obj.status === 200) {
                window.location.href = base_url + "product-list";
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });

      $("#edit_product").validate({
        rules: {
          name: "required",
          category: "required",
          subcategory: "required",
          unit_value: "required",
          unit: "required",
          price: "required",
          sale_price: "required",
          description: "required",
        },
        messages: {
          name: lg_please_enter_pr,
          category: lg_please_select_p1,
          subcategory: lg_please_select_p2,
          unit_value: lg_please_enter_un,
          unit: lg_please_select_u,
          price: lg_please_enter_pr1,
          sale_price: lg_please_enter_se1,
          description: lg_please_enter_de1,
          manufactured_by: lg_please_enter_th,
          short_description: lg_please_enter_th1,
          upload_image_url: lg_please_upload_p,
        },
        submitHandler: function (form) {
          if ($("#upload_image_url").val() == "") {
            $("#image-error").show();
            $("#image-error").html(lg_please_upload_p);
            return false;
          }

          $.ajax({
            url: base_url + "pharmacy/update_product",
            data: $("#edit_product").serialize(),
            type: "POST",
            beforeSend: function () {
              $("#product_btn").attr("disabled", true);
              $("#product_btn").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
            },
            success: function (res) {
              $("#product_btn").attr("disabled", false);
              $("#product_btn").html(lg_update);
              var obj = JSON.parse(res);

              if (obj.status === 200) {
                window.location.href = base_url + "product-list";
              } else {
                toastr.error(obj.msg);
              }
            },
          });
          return false;
        },
      });
    });

    function remove_images(image_url, preview_image_url, row_id) {
      $("#remove_image_div_" + row_id).remove();
      var total_array = $("#upload_image_url").val();
      var arr = total_array.split(",");
      var itemtoRemove = data.image_url;
      arr.splice($.inArray(itemtoRemove, arr), 1);
      $("#upload_image_url").val(arr);

      var total_array1 = $("#upload_preview_image_url").val();
      var arr1 = total_array1.split(",");
      var itemtoRemove1 = data.image_url;
      arr1.splice($.inArray(itemtoRemove1, arr1), 1);
      $("#upload_preview_image_url").val(arr1);
    }

    function remove_image(image_url, preview_image_url, row_id) {
      var url = base_url + "pharmacy/delete_image";

      $.ajax({
        type: "post",
        url: url,
        dataType: "json",

        data: {
          image_url: image_url,
          preview_image_url: preview_image_url,
        },

        success: function (data) {
          if (data.html == 1) {
            $("#remove_image_div_" + row_id).remove();
            var total_array = $("#upload_image_url").val();
            var arr = total_array.split(",");
            var itemtoRemove = data.image_url;
            arr.splice($.inArray(itemtoRemove, arr), 1);
            $("#upload_image_url").val(arr);

            var total_array1 = $("#upload_preview_image_url").val();
            var arr1 = total_array1.split(",");
            var itemtoRemove1 = data.image_url;
            arr1.splice($.inArray(itemtoRemove1, arr1), 1);
            $("#upload_preview_image_url").val(arr1);
          }
        },
      });
    }
  }
}

if (pages == "notification") {
  function delete_notification(id) {
    $.ajax({
      type: "POST",
      url: base_url + "dashboard/delete_notification",
      data: { id: id },
      dataType: "json",
      success: function (response) {
        // return false;
        if (response.status == "200") {
          toastr.success(response.msg);

          setTimeout(function () {
            location.reload(true);
          }, 1000);
        } else {
          toastr.error(response.msg);
        }
      },
    });
  }

  notification(0);

  function notification(load_more) {
    if (load_more == 0) {
      $("#page_no_hidden").val(1);
    }

    var page = $("#page_no_hidden").val();
    var order_by = "DESC";
    var keywords = $("#keywords").val();

    //$('#search-error').html('');

    $.ajax({
      url: base_url + "dashboard/search_notification",
      type: "POST",
      data: {
        page: page,
        order_by: order_by,
        keywords: keywords,
      },
      beforeSend: function () {
        $("#loading").show();
      },
      complete: function () {
        $("#loading").hide();
      },
      success: function (response) {
        //$('#doctor-list').html('');
        if (response) {
          var obj = $.parseJSON(response);
          if (obj.data.length >= 1) {
            var html = "";

            $(obj.data).each(function () {
              html +=
                '<div class="noti-list">' +
                '<div class="noti-avatar">' +
                '<img alt="avatar" src="' +
                this.profile_image +
                '">' +
                "</div>" +
                '<div class="noti-content">' +
                '<span class="truncate head-notifications">' +
                this.from_name +
                "</span>" +
                '<span class="notifications-time">' +
                this.notification_date +
                "</span>" +
                '<div class="clearfix"></div>' +
                '<p class="truncate">' +
                this.text +
                "</p>" +
                '<p class="truncate">' +
                this.to_name +
                "</p>" +
                "</div>" +
                // '<div class="noti-delete">'+
                //   '<button class="text-danger" type="button"><i class="fa fa-trash" onclick="delete_notification('+this.id+')"></i></button>'+
                // '</div>'+
                "</div>";
            });

            if (obj.current_page_no == 1) {
              $("#notification-list").html(html);
            } else {
              $("#notification-list").append(html);
            }
          } else {
            var html =
              '<div class="col-md-12">' +
              '<div class="card">' +
              '<div class="card-body">' +
              '<p class="mb-0">No Notifications found</p>' +
              "</div>" +
              "</div>";
            ("</div>");

            $("#notification-list").html(html);
          }

          var minimized_elements = $("h4.minimize");
          minimized_elements.each(function () {
            var t = $(this).text();
            if (t.length < 100) return;
            $(this).html(
              t.slice(0, 100) +
                '<span>... </span><a href="#" class="more">Load More</a>' +
                '<span style="display:none;">' +
                t.slice(100, t.length) +
                ' <a href="#" class="less">Load less</a></span>'
            );
          });

          $(".search-results").html(obj.count);

          if (obj.count == 0) {
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
            return false;
          }

          if (obj.current_page_no == 1 && obj.count < 5) {
            $("page_no_hidden").val(1);
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
            return false;
          }

          if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
            $("#load_more_btn").removeClass("d-none");
            $("#no_more").addClass("d-none");
          } else {
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
          }
        }
      },
    });
  }

  $("#load_more_btn").click(function () {
    var page_no = $("#page_no_hidden").val();
    var current_page_no = 0;

    if (page_no == 1) {
      current_page_no = 2;
    } else {
      current_page_no = Number(page_no) + 1;
    }
    $("#page_no_hidden").val(current_page_no);
    notification(1);
  });
}

if (modules == "home" && pages == "labs_searchmap") {
  function reset_lab() {
    $("#orderby").val("");
    $("#keywords").val("");
    $("#country").val("");
    $("#state").val("");
    $("#city").val("");
    search_lab(0);
  }
  search_lab(0);
  function search_lab(load_more) {
    if (load_more == 0) {
      $("#page_no_hidden").val(1);
    }
    var page = $("#page_no_hidden").val();
    var city = $("#city").val();
    var state = $("#state").val();
    var country = $("#country").val();
    var keywords = $("#keywords").val();

    $.ajax({
      url: base_url + "home/search_lab",
      type: "POST",
      data: {
        page: page,
        keywords: keywords,
        city: city,
        state: state,
        country: country,
      },
      beforeSend: function () {
        // $('#doctor-list').html('<div class="spinner-border text-success text-center" role="status"></div>');
      },
      success: function (response) {
        if (response) {
          var obj = $.parseJSON(response);
          if (obj.data.length >= 1) {
            var html = "";
            $(obj.data).each(function () {
              html +=
                '<div class="card">' +
                '<div class="card-body">' +
                '<div class="doctor-widget">' +
                '<div class="doc-info-left">' +
                '<div class="doctor-img">' +
                '<a href="#">' +
                '<img src="' +
                this.profileimage +
                '" class="img-fluid" alt="User Image">' +
                "</a>" +
                "</div>" +
                '<div class="doc-info-cont">' +
                '<h4 class="doc-name"><a href="#">' +
                this.first_name +
                " " +
                this.last_name +
                "</a></h4>" +
                "<span>Lab</span></div>" +
                "</div>" +
                '<div class="doc-info-right">' +
                '<div class="clini-infos">' +
                "<ul>" +
                '<li><i class="fas fa-map-marker-alt"></i> ' +
                this.cityname +
                ", " +
                this.countryname +
                "</li>" +
                "</ul>" +
                "</div>" +
                '<div class="clinic-booking">' +
                // '<a class="view-pro-btn" href="#">'+lg_view_profile+'</a>'+
                '<a class="apt-btn" href="' +
                base_url +
                "lab-tests/" +
                this.username +
                '">View Tests</a>' +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>" +
                "</div>";
            });

            if (obj.current_page_no == 1) {
              $("#doctor-list").html(html);
            } else {
              $("#doctor-list").append(html);
            }
          } else {
            var html =
              '<div class="card">' +
              '<div class="card-body">' +
              '<div class="doctor-widget">' +
              "<p>No labs found !!!</p>" +
              "</div>" +
              "</div>" +
              "</div>";
            $("#doctor-list").html(html);
          }

          var minimized_elements = $("h4.minimize");
          minimized_elements.each(function () {
            var t = $(this).text();
            if (t.length < 100) return;
            $(this).html(
              t.slice(0, 100) +
                '<span>... </span><a href="#" class="more">' +
                lg_more +
                "</a>" +
                '<span style="display:none;">' +
                t.slice(100, t.length) +
                ' <a href="#" class="less">' +
                lg_less +
                "</a></span>"
            );
          });

          $(".search-results").html(
            "<span>" + obj.count + " " + lg_matches_for_you + "" + "</span>"
          );
          // $(".widget-title").html(obj.count+' Matches for your search');
          if (obj.count == 0) {
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
            return false;
          }
          if (obj.current_page_no == 1 && obj.count < 5) {
            $("page_no_hidden").val(1);
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
            return false;
          }

          if (obj.total_page > obj.current_page_no && obj.total_page != 0) {
            $("#load_more_btn").removeClass("d-none");
            $("#no_more").addClass("d-none");
          } else {
            $("#load_more_btn").addClass("d-none");
            $("#no_more").removeClass("d-none");
          }
        }
      },
    });
    $("#load_more_btn").click(function () {
      var page_no = $("#page_no_hidden").val();
      var current_page_no = 0;

      if (page_no == 1) {
        current_page_no = 2;
      } else {
        current_page_no = Number(page_no) + 1;
      }
      $("#page_no_hidden").val(current_page_no);
      search_lab(1);
    });
  }
}

if (modules == "home" && pages == "lab_tests_preview") {
  var maxDate = $("#maxDate").val();
  $("#lab_test_book_date").datepicker({
    format: "yyyy-mm-dd",
    startDate: "today",
    //endDate:maxDate,
    autoclose: true,
    todayHighlight: true,
  });

  $(document).on("click", ".lab_test_book_btn", function () {
    booking_date = $("#lab_test_book_date").val();
    lab_id = $("#lab_id").val();
    if ($(".lab_test_chk:checked").length == 0) {
      toastr.error(lg_please_select_a2);
      return false;
    }
    if ($.trim(booking_date) == "") {
      toastr.error(lg_please_select_t3);
      return false;
    }

    // get checked checkbox values..
    var booked_test_arr = [];
    $(".lab_test_chk:checked").each(function () {
      booked_test_arr.push($(this).val());
    });

    // get checked checkbox values price..
    var booked_price_arr = [];
    $(".lab_test_chk:checked").each(function () {
      booked_price_arr.push($(this).data("price"));
    });

    //console.log(booked_test_arr);
    if (booked_test_arr.length > 0) {
      $.ajax({
        url: base_url + "lab/set_booked_session_lab_test",
        type: "POST",
        data: {
          booking_ids: JSON.stringify(booked_test_arr),
          lab_id: lab_id,
          lab_username: $("#lab_username").val(),
          lab_test_date: $("#lab_test_book_date").val(),
          booking_ids_price: JSON.stringify(booked_price_arr),
        },
        beforeSend: function () {
          $("#lab_test_book_btn").attr("disabled", true);
          $("#lab_test_book_btn").html(
            '<div class="spinner-border text-success text-center" role="status"></div>'
          );
        },
        success: function (res) {
          $("#lab_test_book_btn").attr("disabled", false);
          $("#change_btn").html("Proceed to pay");
          //console.log(response); return false;
          var obj = JSON.parse(res);
          if (obj.status === 200) {
            setTimeout(function () {
              window.location = base_url + "lab/checkout";
            }, 1000);
          } else {
            toastr.success(lg_error_while_boo);
            setTimeout(function () {
              window.location.href = base_url + "dashboard";
            }, 2000);
          }
        },
      });
    }
  });
}

if (modules == "lab" && pages == "checkout") {
  function appoinment_payment(type) {
    // var terms_accept=$("input[name='terms_accept']:checked").val();
    var terms_accept = 1;
    if (terms_accept == "1") {
      if (type == "paypal") {
        $("#payment_formid").submit();
      } else if (type == "Cybersource") {
        $("#payment_confirmation").submit();
        return false;
      } else {
        var payment_method = $("input[name='payment_methods']:checked").val();
        if (payment_method != "Card Payment") {
          $("#my_book_appoinment").click();
        }

        return false;
      }
    } else {
      toastr.warning(lg_please_accept_t);
    }
  }

  var stripe = Stripe(stripe_api_key);

  // Create an instance of Elements.
  var elements = stripe.elements();

  // Custom styling can be passed to options when creating an Element.
  // (Note that this demo uses a wider set of styles than the guide below.)
  var style = {
    base: {
      color: "#32325d",
      fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
      fontSmoothing: "antialiased",
      fontSize: "16px",
      "::placeholder": {
        color: "#aab7c4",
      },
    },
    invalid: {
      color: "#fa755a",
      iconColor: "#fa755a",
    },
  };

  // Create an instance of the card Element.
  var card = elements.create("card", { style: style });

  // Add an instance of the card Element into the `card-element` <div>.
  card.mount("#card-element");

  // Handle real-time validation errors from the card Element.
  card.addEventListener("change", function (event) {
    var displayError = document.getElementById("card-errors");
    if (event.error) {
      displayError.textContent = event.error.message;
      $("#stripe_pay_btn").attr("disabled", false);
      $("#stripe_pay_btn").html("Confirm and Pay");
    } else {
      displayError.textContent = "";
    }
  });

  // Handle form submission.
  var form = document.getElementById("payment-form");
  form.addEventListener("submit", function (event) {
    event.preventDefault();
    $("#stripe_pay_btn").attr("disabled", true);
    $("#stripe_pay_btn").html(
      '<div class="spinner-border text-light" role="status"></div>'
    );
    stripe
      .createPaymentMethod({
        type: "card",
        card: card,
      })
      .then(stripePaymentMethodHandler);
  });

  function stripePaymentMethodHandler(result) {
    if (result.error) {
      $("#card-errors").text(result.error.message);
      $("#stripe_pay_btn").attr("disabled", false);
      $("#stripe_pay_btn").html("Confirm and Pay");
      // Show error in payment form
    } else {
      $.ajax({
        url: base_url + "lab/stripe_payment",
        data: { payment_method_id: result.paymentMethod.id },
        type: "POST",
        dataType: "JSON",
        beforeSend: function () {
          $("#stripe_pay_btn").attr("disabled", true);
          $("#stripe_pay_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (response) {
          handleServerResponse(response);
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  }

  function handleServerResponse(response) {
    if (response.status == "500") {
      toastr.error(response.message);
      $("#stripe_pay_btn").attr("disabled", false);
      $("#stripe_pay_btn").html("Confirm and Pay");
    } else if (response.status == "201") {
      // Use Stripe.js to handle required card action
      stripe
        .handleCardAction(response.payment_intent_client_secret)
        .then(handleStripeJsResult);
    } else {
      if (response.status == "200") {
        toastr.success(lg_lab_appointment);
        setTimeout(function () {
          window.location.href = base_url + "dashboard";
        }, 2000);
      } else {
        toastr.error(lg_something_went_1);
        setTimeout(function () {
          window.location.href = base_url + "dashboard";
        }, 2000);
      }
    }
  }

  function handleStripeJsResult(result) {
    if (result.status == "500") {
      toastr.error(result.message);
    } else {
      $.ajax({
        url: base_url + "lab/stripe_payment",
        data: { payment_intent_id: result.paymentIntent.id },
        type: "POST",
        dataType: "JSON",
        beforeSend: function () {
          $("#stripe_pay_btn").attr("disabled", true);
          $("#stripe_pay_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (response) {
          handleServerResponse(response);
        },
        error: function (error) {
          console.log(error);
        },
      });
    }
  }

  // function stripeTokenHandler(token) {
  //     $('#access_token').val(token.id);
  //     $.ajax({
  //         url: base_url+'lab/stripe_pay',
  //         data: $('#payment_formid').serialize(),
  //         type: 'POST',
  //         dataType: 'JSON',
  //         beforeSend: function(){
  //             $('#stripe_pay_btn').attr('disabled',true);
  //             $('#stripe_pay_btn').html('<div class="spinner-border text-light" role="status"></div>');
  //         },
  //         success: function(response){
  //             // $('.overlay').hide();
  //             if(response.status=='200')
  //             {
  //                 toastr.success('Your lab tst booked successfully.');
  //                 setTimeout(function() {
  //                     window.location.href=base_url+'dashboard';
  //                 }, 2000);
  //             }
  //             else
  //             {
  //                 toastr.error('Lab booking Failled');
  //                 setTimeout(function() {
  //                     window.location.href=base_url+'dashboard';
  //                 }, 2000);
  //             }
  //         },
  //         error: function(error){
  //             console.log(error);
  //         }

  //     });
  // }

  $(document).ready(function () {
    $("input[type=radio][name=payment_methods]").change(function () {
      if (this.value == "Card Payment") {
        $(".stripe_payment").show();
        $(".paypal_payment").hide();
        $(".cybersource_payment").hide();
        $("#payment_method").val("Card Payment");
      } else if (this.value == "PayPal") {
        $(".stripe_payment").hide();
        $(".paypal_payment").show();
        $(".cybersource_payment").hide();
        $("#payment_method").val("Card Payment");
      } else if (this.value == "Cybersource") {
        $(".stripe_payment").hide();
        $(".paypal_payment").show();
        $(".cybersource_payment").show();
        $("#payment_method").val("Card Payment");
      } else if (this.value == "Pay on Arrive") {
        $(".stripe_payment").hide();
        $(".paypal_payment").hide();
        $(".cybersource_payment").hide();
        $("#payment_method").val("Pay on Arrive");
      } else {
        $(".stripe_payment").hide();
        $(".paypal_payment").hide();
        $("#payment_method").val("");
      }
    });
  });
}

if (
  modules == "lab" &&
  (pages == "lab_tests" || pages == "lab_appointment_list")
) {
  $(document).ready(function () {
    //datatables
    var lab_table;

    lab_table = $("#lab_table").DataTable({
      ordering: true,
      processing: true, //Feature control the processing indicator.
      bnDestroy: true,
      serverSide: true, //Feature control DataTables' server-side processing mode.
      order: [], //Initial no order.

      // Load data for the table's content from an Ajax source
      ajax: {
        url: base_url + "lab/lab_list",
        type: "POST",
        data: function (data) {},
        error: function () {},
      },
      //Set column definition initialisation properties.
      columnDefs: [
        {
          targets: [0, 4], //first column / numbering column
          orderable: false, //set not orderable
        },
      ],
    });
  });

  function add_lab_test() {
    $('[name="method"]').val("insert");
    $("#lab_form")[0].reset(); // reset form on modals
    $("#lab_modal").modal("show"); // show bootstrap modal
    $(".modal-title").text("Add new lab test"); // Set Title to Bootstrap modal title
  }

  $(document).ready(function () {
    /*$("#lab_form").on('submit',(function(e){
            e.preventDefault();
            var lab_test_name = $('#lab_test_name').val();
            var amount = $('#amount').val();
            if(lab_test_name==''){
                toastr.error('Test name field is required.');
                return false;
            }
            if(amount==''){
                toastr.error('Amount field is required.');
                return false;
            }
       
            $.ajax({
                url: base_url+'lab/lab_test_save',
                type: "POST",
                data:  new FormData(this),
                contentType: false,
                cache: false,
                processData:false,
                beforeSend:function() { 

                    $('#btnlabtestsave').html('<div class="spinner-border text-light" role="status"></div>');
                    $('#btnlabtestsave').attr('disabled',true);

                },
                success: function(data){
                    //console.log(data);
                    $('#btnlabtestsave').html('Save');
                    $('#btnlabtestsave').attr('disabled',false);
                    var obj=jQuery.parseJSON(data);
                    $('#lab_modal').modal('hide');
                    if(obj.status==200)
                    {
                        toastr.success(obj.msg);
                        setTimeout(function(){ window.location.href=base_url+'lab-test'; }, 1000);
                       // lab_table.ajax.reload(null,false);
                    } else {
                        toastr.error(obj.msg);
                        setTimeout(function(){ window.location.href=base_url+'lab-test'; }, 1000);
                        //lab_table.ajax.reload(null,false);
                    }
                }

            });
        }));*/

    /*submit form ajax template*/
    $("#lab_form")
      .submit(function (e) {
        e.preventDefault();
      })
      .validate({
        rules: {
          lab_test_name: {
            required: true,
            maxlength: 100,
            accept_chars: true,
          },
          amount: {
            required: true,
            maxlength: 100,
            number: true,
          },
          duration: {
            required: true,
            maxlength: 100,
            accept_chars: true,
          },
          description: {
            required: true,
            maxlength: 500,
            accept_chars: true,
          },
        },
        messages: {
          lab_test_name: {
            required: lg_form_lab_test_testname_req,
            maxlength: lg_form_lab_test_testname_max,
          },
          amount: {
            required: lg_form_lab_test_amount_req,
            maxlength: lg_form_lab_test_amount_max,
          },
          duration: {
            required: lg_form_lab_test_duration_req,
            maxlength: lg_form_lab_test_duration_max,
          },
          description: {
            required: lg_form_lab_test_description_req,
            maxlength: lg_form_lab_test_description_max,
          },
        },
        submitHandler: function (form) {
          // form data
          var formData = new FormData($("#lab_form")[0]);

          // ajax
          $.ajax({
            url: base_url + "lab/lab_test_save",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
              $("#btnlabtestsave").html(
                '<div class="spinner-border text-light" role="status"></div>'
              );
              $("#btnlabtestsave").attr("disabled", true);
            },
            success: function (data) {
              //console.log(data);
              $("#btnlabtestsave").html("Save");
              $("#btnlabtestsave").attr("disabled", false);
              var obj = jQuery.parseJSON(data);
              $("#lab_modal").modal("hide");
              if (obj.status == 200) {
                toastr.success(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "lab-test";
                }, 1000);
                // lab_table.ajax.reload(null,false);
              } else {
                toastr.error(obj.msg);
                setTimeout(function () {
                  window.location.href = base_url + "lab-test";
                }, 1000);
                //lab_table.ajax.reload(null,false);
              }
            },
          });

          return false;
        },
      });
    /*submit form ajax template*/
  });

  function edit_lab_test(id) {
    $('[name="method"]').val("update");
    $("#lab_form")[0].reset(); // reset form on modals

    //Ajax Load data from ajax
    $.ajax({
      url: base_url + "lab/lab_test_edit/" + id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        $('[name="id"]').val(data.id);
        $('[name="lab_test_name"]').val(data.lab_test_name);
        $('[name="amount"]').val(data.amount);
        $('[name="duration"]').val(data.duration);
        $('[name="description"]').val(data.description);
        $("#lab_modal").modal("show"); // show bootstrap modal when complete loaded
        $(".modal-title").text("Edit lab test"); // Set title to Bootstrap modal title
      },
      error: function () {
        window.location.href = base_url + "dashboard";
      },
    });
  }

  function delete_lab_test(id, status) {
    $("#lab_test_id").val(id);
    $("#lab_test_status").val(status);
    $("#delete_lab_test").modal("show");
  }

  function lab_test_tables() {
    lab_table.ajax.reload(null, false);
  }

  function lab_test_delete() {
    var id = $("#lab_test_id").val();
    var status = $("#lab_test_status").val();
    $("#change_btn").attr("disabled", true);
    $("#change_btn").html(
      '<div class="spinner-border text-light" role="status"></div>'
    );
    $.post(
      base_url + "lab/lab_test_delete",
      { id: id, status: status },
      function (res) {
        $("#change_btn").attr("disabled", false);
        $("#change_btn").html("Yes");
        $("#delete_lab_test").modal("hide");
        var obj = jQuery.parseJSON(res);
        toastr.success(obj.msg);
        // lab_table.ajax.reload(null,false);
        setTimeout(function () {
          window.location.href = base_url + "lab-test";
        }, 1000);
      }
    );
  }
}

if (modules == "lab" && pages == "lab_profile") {
  var maxDate = $("#maxDate").val();
  $("#dob").datepicker({
    startView: 2,
    format: "dd/mm/yyyy",
    autoclose: true,
    todayHighlight: true,
    endDate: maxDate,
  });

  $("#lab_profile_form").validate({
    rules: {
      first_name: "required",
      last_name: "required",
      country_code: {
        required: true,
      },
      gender: "required",
      dob: "required",
      // blood_group: "required",
      address1: {
        required: true,
        address_validation: true,
        maxlength: 500,
      },
      address2: {
        address_validation: true,
        maxlength: 500,
      },
      country: "required",
      state: "required",
      city: "required",
      postal_code: {
        required: true,
        minlength: 4,
        maxlength: 7,
        digits: true,
      },
    },
    messages: {
      first_name: lg_please_enter_yo,
      last_name: lg_please_enter_yo1,
      country_code: {
        required: lg_please_select_c_code,
      },
      gender: lg_please_select_g,
      dob: lg_please_enter_yo2,
      // blood_group: lg_please_select_b,
      address1: {
        required: lg_pers_info_address_req,
        address_validation: lg_pers_info_address_val,
        maxlength: lg_enter_address_max,
      },
      address2: {
        address_validation: lg_pers_info_address_val,
        maxlength: lg_enter_address_max,
      },
      country: lg_please_select_c,
      state: lg_please_select_s,
      city: lg_please_select_c1,
      postal_code: {
        required: lg_please_enter_po,
        maxlength: lg_please_enter_va2,
        minlength: lg_please_enter_va2,
        digits: lg_please_enter_va2,
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: base_url + "profile/update_lab_profile",
        data: $("#lab_profile_form").serialize(),
        type: "POST",
        beforeSend: function () {
          $("#save_btn").attr("disabled", true);
          $("#save_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (res) {
          $("#save_btn").attr("disabled", false);
          $("#save_btn").html(lg_save_changes);
          var obj = JSON.parse(res);
          if (obj.status === 200) {
            toastr.success(obj.msg);
            setTimeout(function () {
              window.location.href = base_url + "dashboard";
            }, 2000);
          } else {
            toastr.error(obj.msg);
          }
        },
      });
      return false;
    },
  });
}

if (pages == "appointments") {
  var lab_appointment_table;
  lab_appointment_table = $("#lab_appointment_table").DataTable({
    ordering: true,
    processing: true, //Feature control the processing indicator.
    bnDestroy: true,
    serverSide: true, //Feature control DataTables' server-side processing mode.
    order: [], //Initial no order.

    // Load data for the table's content from an Ajax source
    ajax: {
      url: base_url + "lab/lab_appointment_list",
      type: "POST",

      data: function (data) {},
      error: function () {},
    },

    //Set column definition initialisation properties.
    columnDefs: [
      {
        targets: [0], //first column / numbering column
        orderable: false, //set not orderable
      },
    ],
  });

  $(document).on("change", ".lab_appointment_status", function () {
    var id = $(this).attr("id");
    var status = $(this).val();
    $.post(
      base_url + "lab/change_appointment_status",
      { id: id, status: status },
      function (data) {
        toastr.success(lg_status_updated_);
        lab_appointment_table.ajax.reload(null, false);
      }
    );
  });

  function lab_appoinments_table() {
    lab_appointment_table.ajax.reload(null, false);
  }

  function view_docs(id) {
    var base = $("#base").val();

    $.ajax({
      url: base_url + "appoinments/get_docs/" + id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        //console.log(data);
        //console.log("test");
        var li = "";
        $.each(data, function (index, value) {
          var link = base + value;
          //alert

          var index = index + 1;

          li +=
            '<li><a target="_blank" href="' +
            link +
            '">' +
            index +
            " . View Document</a></li>";
        });
        var len = data.length;

        if (len > 0) {
          $("#links").html(li);
        } else {
          $("#links").html("No records found");
        }

        $("#view_docs").modal("show");
      },
      error: function () {
        window.location.href = base_url + "admin/dashboard";
        return false;
      },
    });
  }
}

if (pages == "lab_appoinments") {
  var lab_appointment_table;
  lab_appointment_table = $("#lab_appointment_table").DataTable({
    ordering: false,
    processing: true, //Feature control the processing indicator.
    bnDestroy: true,
    serverSide: true, //Feature control DataTables' server-side processing mode.
    order: [], //Initial no order.

    // Load data for the table's content from an Ajax source
    ajax: {
      url: base_url + "appoinments/lab_appointment_list",
      type: "POST",

      data: function (data) {},
      error: function () {},
    },

    //Set column definition initialisation properties.
    columnDefs: [
      {
        targets: [0], //first column / numbering column
        orderable: false, //set not orderable
      },
    ],
  });

  function lab_appoinments_table() {
    lab_appointment_table.ajax.reload(null, false);
  }

  function view_docs(id) {
    var base = $("#base").val();

    $.ajax({
      url: base_url + "appoinments/get_docs/" + id,
      type: "GET",
      dataType: "JSON",
      success: function (data) {
        //console.log(data);
        var li = "";
        $.each(data, function (index, value) {
          var link = base_url + value;
          var file_path = value.split("/");
          file_name = file_path[file_path.length - 1];

          var index = index + 1;
          li +=
            '<li><a target="_blank" href="' +
            link +
            '">' +
            index +
            " . " +
            file_name +
            "</a></li>";
        });

        if (data.length > 0) {
          $("#links").html(li);
        } else {
          $("#links").html("No records found");
        }

        $("#view_docs").modal("show");
      },
      error: function () {
        window.location.href = base_url + "admin/dashboard";
        return false;
      },
    });
  }
}

if (modules == "lab" && pages == "appointments") {
  function upload_lab_docs(id) {
    // reset form values
    $("#upload_lab_form")[0].reset();

    $('[name="appointment_id"]').val(id);
    $("#upload_labdocs_modal").modal("show"); // show bootstrap modal
  }

  $("#upload_lab_form").submit(function (e) {
    e.preventDefault();
    var formData = new FormData($("#upload_lab_form")[0]);

    var oFile = document.getElementById("user_files_mr").files[0];
    if (!document.getElementById("user_files_mr").files[0]) {
      toastr.warning(lg_please_select_f1);
      return false;
    }

    var fileInput = $("#user_files_mr")[0];
    $.each(fileInput.files, function (k, file) {
      formData.append("user_file[]", file);
    });

    $.ajax({
      url: base_url + "lab/lab_upload_docs",
      type: "POST",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      beforeSend: function () {
        if (oFile) {
          $("#medical_btn").attr("disabled", true);
          $("#medical_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        }
      },
      success: function (res) {
        $("#medical_btn").attr("disabled", false);
        $("#medical_btn").html(lg_submit);
        $("#upload_labdocs_modal").modal("hide");
        var obj = jQuery.parseJSON(res);
        if (obj.status === 500) {
          toastr.warning(obj.msg);
          $("#user_files_mr").val("");
        } else {
          $("#upload_lab_form")[0].reset();
          toastr.success(obj.msg);
          window.location.reload();
        }
      },
    });
    return false;
  });
}
if (modules == "doctor" && pages == "appoinments") {
  function conversation_complete_status(id) {
    $("#app-complete-modal-title").html(lg_complete);
    $("#complete_appoinments_id").val(id);
    $("#appoinments_status_complete_modal").modal("show");
  }
  function change_complete_status() {
    var id = $("#complete_appoinments_id").val(id);
    $.post(
      base_url + "appoinments/change_complete_status",
      { appoinments_id: id },
      function (res) {
        $("#change_complete_btn").attr("disabled", true);
        $("#appoinments_status_modal").modal("hide");
        my_appoinments(0);
      }
    );
  }
}

if (pages == "lab_dashboard") {
  var lab_appoinments;

  lab_appoinments = $("#lab_appoinments_table").DataTable({
    ordering: true,
    processing: true, //Feature control the processing indicator.
    bnDestroy: true,
    serverSide: true, //Feature control DataTables' server-side processing mode.
    order: [], //Initial no order.
    language: {
      infoFiltered: "",
    },
    // Load data for the table's content from an Ajax source
    ajax: {
      url: base_url + "dashboard/lab_appointment_list",
      type: "POST",
      data: function (data) {
        data.type = $("#type").val();
      },
      error: function () {},
    },

    //Set column definition initialisation properties.
    columnDefs: [
      {
        targets: [0, 4], //first column / numbering column
        orderable: false, //set not orderable
      },
    ],
  });

  function lab_appoinments_table(type) {
    $("#type").val(type);
    lab_appoinments.ajax.reload(null, false); //reload datatable ajax
  }
  lab_appoinments_table(1);
}

if (pages == "add_doctor") {
  var appoinment_table;
  console.log("add_doctor");
  appoinment_table = $("#doctor_table").DataTable({
    ordering: true,
    processing: true,
    bnDestroy: true,
    serverSide: true,
    order: [],
    language: {
      infoFiltered: "",
    },
    ajax: {
      url: base_url + "doctor/list_doctors",
      type: "POST",
      data: function (data) {
        data.type = $("#type").val();
      },
      error: function () {},
    },
    columnDefs: [
      {
        targets: [0],
        orderable: false,
      },
    ],
  });
  function appoinments_table(type) {
    $("#type").val(type);
    appoinment_table.ajax.reload(null, false);
  }
  appoinments_table(1);

  var validator = $("#user_modal").validate();
  function add_doct() {
    validator.resetForm();
    $("#user_id").val("");
    $("#country_code").val("").trigger("change");
   
    $("#register_form")[0].reset(); // reset form on modals
    $("#user_modal").modal("show"); // show bootstrap modal
    $("#user_modal .modal-title").text("Add Doctor"); // Set Title to Bootstrap modal title
    $(".pass").show();
  }

  function edit_doctor(pre_id) {
    $("#register_form")[0].reset();
    validator.resetForm();
    $("input").css("color", "#000000");
    $("#user_modal").modal("show");
    $(".pass").hide();
    $("#user_modal .modal-title").text("Edit Doctor");
    $.post(
      base_url + "doctor/get_doctor_details",
      { pre_id: pre_id },
      function (res) {
        var obj = jQuery.parseJSON(res);
        console.log(obj);
        $("#first_name").val(obj.first_name);
        $("#last_name").val(obj.last_name);
        $("#email").val(obj.email);
        $("#country_code").val(obj.country_code).select2();
        $("#mobileno").val(obj.mobile);
        $("#user_id").val(pre_id);
        $("#email").attr("readonly", true);
      }
    );
  }
  function delete_doctor(id) {
    $("#delete_id").val(id);
    $("#delete_table").val("users");
    $("#delete_title").text(lang_doctor);
    $("#delete_modal").modal("show");
  }

  function delete_details() {
    var id = $("#delete_id").val();
    var delete_table = $("#delete_table").val();
    $("#delete_btn").attr("disabled", true);
    $("#delete_btn").html(
      '<div class="spinner-border text-light" role="status"></div>'
    );
    $.post(
      base_url + "my_patients/delete_details",
      { id: id, delete_table, delete_table },
      function (res) {
        if (delete_table == "users") {
          appoinments_table();
        }
        $("#delete_btn").attr("disabled", false);
        $("#delete_btn").html(lg_yes);
        $("#delete_modal").modal("hide");
      }
    );
  }

  $("#register_form").validate({
    rules: {
      first_name: "required",
      last_name: "required",
      country_code: "required",
      mobileno: {
        required: true,
        minlength: 7,
        maxlength: 12,
        digits: true,
        remote: {
          url: base_url + "doctor/check_mobileno",
          type: "post",
          data: {
            mobileno: function () {
              return $("#mobileno").val();
            },
            id: function () {
              return $("#user_id").val();
            },
          },
        },
      },
      email: {
        required: true,
        email: true,
        remote: {
          url: base_url + "doctor/check_email",
          type: "post",
          data: {
            email: function () {
              return $("#email").val();
            },
            id: function () {
              return $("#user_id").val();
            },
          },
        },
      },
      password: {
        required: true,
        minlength: 6,
      },
      confirm_password: {
        required: true,
        equalTo: "#password",
      },
    },
    messages: {
      first_name: "Please enter your first name",
      last_name: "Please enter your last name",
      country_code: "Please select country code",
      mobileno: {
        required: "Please enter mobile number",
        maxlength: "Please enter valid mobileno",
        minlength: "Please enter valid mobileno",
        digits: "Please enter valid mobileno",
        remote: "Your mobile no already exits",
      },
      email: {
        required: "Please enter email",
        email: "Please enter valid email address",
        remote: "Your email address already exist",
      },
      password: {
        required: "Please enter password",
        minlength: "Your password must be 6 characters",
      },
      confirm_password: {
        required: "Please enter confirm password",
        equalTo: "Your password does not match",
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: base_url + "doctor/add_doctor",
        data: $("#register_form").serialize(),
        type: "POST",
        beforeSend: function () {
          $("#register_btn").attr("disabled", true);
          $("#register_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (res) {
          $("#register_btn").attr("disabled", false);
          $("#register_btn").html("Submit");
          var obj = JSON.parse(res);

          if (obj.status === 200) {
            $("#user_modal").modal("hide");
            $("#register_form")[0].reset();
            appoinments_table();
          } else {
            toastr.error(obj.msg);
          }
        },
      });
      return false;
    },
  });
}

if (pages == "add_team") {
  var team_table;
  console.log(pages);
  

  team_table = $("#team_table").DataTable({
    ordering: true,
    processing: true,
    bnDestroy: true,
    serverSide: true,
    order: [],
    language: {
      infoFiltered: "",
    },
    ajax: {
      url: base_url + "team/list_team",
      type: "POST",
      data: function (data) {
        data.type = $("#type").val();
      },
      error: function () {},
    },
    columnDefs: [
      {
        targets: [0],
        orderable: false,
      },
    ],
  });
  function teams_table(type) {
    $("#type").val(type);
    team_table.ajax.reload(null, false);
  }
  teams_table(1);

  var validator = $("#user_modal").validate();
  // console.log("before add_team_member");
  function add_team_member() {
    // Reset the form validation and fields
    validator.resetForm();

    // Clear the user ID field
    $("#user_id").val("");

    // Clear the country code dropdown and trigger change event
    $("#country_code").val("").trigger("change");


    // Reset the form fields in the modal
    $("#register_form")[0].reset();

    // Show the modal to add a new team member
    $("#user_modal").modal("show");

    // Set the modal title
    $("#user_modal .modal-title").text("Add Team Member");

    // Show the password field (if needed)
    $(".pass").show();

    // Fix: Assuming you're getting the value of a specific field (e.g., user_id)
    var userId = $("#user_id").val(); // Get the value from user_id (can change to another element if needed)
   console.log(userId);
    // Make the AJAX request to get country codes
    $.ajax({
        type: "GET",
        url: base_url + "ajax/get_country_code", // The URL to fetch country codes
        data: { id: userId }, // Send the user_id or any other identifier as the data
        beforeSend: function () {
            // Show "Please wait" message in the first option of the country code dropdown
            $("#country_code").find("option:eq(0)").html(lg_please_wait);
        },
        success: function (data) {
            // Reset the first option text when the data is successfully loaded
            $("#country_code").find("option:eq(0)").html(lg_select_country_);
            
            // Parse the response data (assuming it's in JSON format)
            var obj = jQuery.parseJSON(data);

            // Loop through each item in the response and append options to the dropdown
            $(obj).each(function () {
                var option = $("<option />");  // Create a new <option> element
                option.attr("value", this.value).text(this.label); // Set value and label from the response
                $("#country_code").append(option); // Append the new option to the dropdown
            });

            // If a global `country_code` variable is defined, set it as the selected value
            if (typeof country_code !== "undefined") {
                $("#country_code").val(country_code); // Set the country_code dropdown to the predefined value
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            // Handle AJAX error (if any)
            console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
            // Optionally show an error message to the user
        }
    });
}



  function edit_team(pre_id) {
    $("#register_form")[0].reset();
    validator.resetForm();
    $("input").css("color", "#000000");
    $("#user_modal").modal("show");
    $(".pass").hide();
    $("#user_modal .modal-title").text("Edit Team member");
    $.post(
      base_url + "team/get_team_details",
      { pre_id: pre_id },
      function (res) {
        var obj = jQuery.parseJSON(res);
        console.log(obj);
        $("#first_name").val(obj.first_name);
        $("#last_name").val(obj.last_name);
        $("#email").val(obj.email);
        $("#country_code").val(obj.country_code).select2();
        $("#mobileno").val(obj.mobile);
        $("#user_id").val(pre_id);
        $("#email").attr("readonly", true);
      }
    );
    $.ajax({
      type: "GET",
      url: base_url + "ajax/get_country_code", // The URL to fetch country codes
      data: { id: pre_id }, // Send the user_id or any other identifier as the data
      beforeSend: function () {
          // Show "Please wait" message in the first option of the country code dropdown
          $("#country_code").find("option:eq(0)").html(lg_please_wait);
      },
      success: function (data) {
          // Reset the first option text when the data is successfully loaded
          $("#country_code").find("option:eq(0)").html(lg_select_country_);
          
          // Parse the response data (assuming it's in JSON format)
          var obj = jQuery.parseJSON(data);

          // Loop through each item in the response and append options to the dropdown
          $(obj).each(function () {
              var option = $("<option />");  // Create a new <option> element
              option.attr("value", this.value).text(this.label); // Set value and label from the response
              $("#country_code").append(option); // Append the new option to the dropdown
          });

          // If a global `country_code` variable is defined, set it as the selected value
          if (typeof country_code !== "undefined") {
              $("#country_code").val(country_code); // Set the country_code dropdown to the predefined value
          }
      },
      error: function (jqXHR, textStatus, errorThrown) {
          // Handle AJAX error (if any)
          console.error("AJAX request failed: " + textStatus + ", " + errorThrown);
          // Optionally show an error message to the user
      }
  });
  }
  function delete_team(id){
          console.log(id);
          if(confirm('Are you sure delete this service?'))
          {
              // ajax delete data to database
              
              $.ajax({
                  url : base_url+"team/team_delete/"+id,
                  type: "POST",
                  dataType: "JSON",
                  success: function(data)
                  {
                      //if success reload ajax table
                      // $('#admin_service_form').modal('hide');
              
                      toastr.success('Team Member deleted successfully');
                      teams_table();
                  },
                  error:function(){
                       window.location.href=base_url+'dashboard';
                      }
              });

          }
      }

  $("#register_form").validate({
    rules: {
      first_name: "required",
      last_name: "required",
      country_code: "required",
      mobileno: {
        required: true,
        minlength: 7,
        maxlength: 12,
        digits: true,
        remote: {
          url: base_url + "doctor/check_mobileno",
          type: "post",
          data: {
            mobileno: function () {
              return $("#mobileno").val();
            },
            id: function () {
              return $("#user_id").val();
            },
          },
        },
      },
      email: {
        required: true,
        email: true,
        remote: {
          url: base_url + "doctor/check_email",
          type: "post",
          data: {
            email: function () {
              return $("#email").val();
            },
            id: function () {
              return $("#user_id").val();
            },
          },
        },
      },
      password: {
        required: true,
        minlength: 6,
      },
      confirm_password: {
        required: true,
        equalTo: "#password",
      },
    },
    messages: {
      first_name: "Please enter your first name",
      last_name: "Please enter your last name",
      country_code: "Please select country code",
      mobileno: {
        required: "Please enter mobile number",
        maxlength: "Please enter valid mobileno",
        minlength: "Please enter valid mobileno",
        digits: "Please enter valid mobileno",
        remote: "Your mobile no already exits",
      },
      email: {
        required: "Please enter email",
        email: "Please enter valid email address",
        remote: "Your email address already exist",
      },
      password: {
        required: "Please enter password",
        minlength: "Your password must be 6 characters",
      },
      confirm_password: {
        required: "Please enter confirm password",
        equalTo: "Your password does not match",
      },
    },
    submitHandler: function (form) {
      $.ajax({
        url: base_url + "team/add_team",
        data: $("#register_form").serialize(),
        type: "POST",
        beforeSend: function () {
          $("#register_btn").attr("disabled", true);
          $("#register_btn").html(
            '<div class="spinner-border text-light" role="status"></div>'
          );
        },
        success: function (res) {
          $("#register_btn").attr("disabled", false);
          $("#register_btn").html("Submit");
          var obj = JSON.parse(res);

          if (obj.status === 200) {
            $("#user_modal").modal("hide");
            $("#register_form")[0].reset();
            teams_table();
          } else {
            toastr.error(obj.msg);
          }
        },
      });
      return false;
    },
  });
}

if (pages == "invoice_checkout") {
  function pay_invoice() {
    $("#payment_confirmation").submit();
    return false;
  }
}

/////////////////////duration//////////////////////
function duration(inputtxt) {
  var letters = /^[0-9a-zA-Z]+$/;
  if (inputtxt.value.match(letters)) {
    //alert('Your registration number have accepted : you can try another');
    document.form1.text1.focus();
    return true;
  } else {
    //alert('Please input alphanumeric characters only');
    return false;
  }
}
//////////////////     Sigin  firstname        //////////////////////////
$('input[name = "first_name"]').on("keypress", function (event) {
  var x = event.which || event.keyCode;
  if ((x >= 65 && x <= 90) || (x >= 97 && x <= 122) || x === 32) {
    return true;
  } else {
    return false;
  }
});

//////////////////     Sigin  Lastname        //////////////////////////
$('input[name = "last_name"]').on("keypress", function (event) {
  var x = event.which || event.keyCode;
  if ((x >= 65 && x <= 90) || (x >= 97 && x <= 122) || x === 32) {
    return true;
  } else {
    return false;
  }
});
function assign_doc() {
  $.ajax({
    type: "POST",
    data: {
      id: $("#assign_doc option:selected").val(),
      app_id: $("#app_id_assign").val(),
    },
    url: base_url + "doctor/assign_doc",

    success: function (data) {
      /*get response as json */
      var obj = jQuery.parseJSON(data);
      if (obj.status == 200) {
        location.reload();
      } else {
      }
      /*ends */
    },
  });
}

function open_status() {
  var hrsopen = $("#hrsopen").val();
  if (hrsopen == "yes") {
    $("#pharmacy_opens_at").prop("disabled", true);
    $("#pharmacy_opens_at").val("");
  }
  if (hrsopen == "no") {
    $("#pharmacy_opens_at").prop("disabled", false);
  }
}

// jquery validate method
$(document).ready(function () {
  // allow only char and spaces
  jQuery.validator.addMethod(
    "text_spaces_only",
    function (value) {
      return /^[a-zA-Z\- ]*$/.test(value);
    },
    lg_validate_text_spaces_only
  );

  // email
  jQuery.validator.addMethod("email", function (value) {
    return /^([a-z0-9]{1,})([.\_])?(([a-z0-9]{1,}))(@)(([a-z1-9]{2,})(\.)[a-z]{2,3})$/.test(
      value
    );
  });

  // address
  jQuery.validator.addMethod("address_validation", function (value) {
    return /^[A-Za-z0-9-,./ ]*$/.test(value);
  });

  // address
  jQuery.validator.addMethod("reviews_validation", function (value) {
    return /^[A-Za-z0-9-,./ ]*$/.test(value);
  });

  jQuery.validator.addMethod(
    "accept_chars",
    function (value) {
      return /^[A-Za-z0-9-,./\:\& ]*$/.test(value);
    },
    lg_accept_chars_val
  );
});
// jquery validate method

// toggle password
const togglePassword1 = document.querySelector("#togglePassword1");
const password1 = document.querySelector("#password");

if (togglePassword1) {
  togglePassword1.addEventListener("click", function (e) {
    // toggle the type attribute
    const type1 =
      password1.getAttribute("type") === "password" ? "text" : "password";
    password1.setAttribute("type", type1);
    // toggle the eye slash icon
    this.classList.toggle("fa-eye-slash");
  });
  // toggle password
}

// toggle confirm password
const togglePassword2 = document.querySelector("#togglePassword2");
const password2 = document.querySelector("#confirm_password");

if (togglePassword2) {
  togglePassword2.addEventListener("click", function (e) {
    // toggle the type attribute
    const type2 =
      password2.getAttribute("type") === "password" ? "text" : "password";
    password2.setAttribute("type", type2);
    // toggle the eye slash icon
    this.classList.toggle("fa-eye-slash");
  });
  // toggle confirm password
}

// disable-cut-copy-paste
$('[name="first_name"], [name="last_name"]').on("cut copy paste", function (e) {
  e.preventDefault();
});

// For special Character and Number validation
$.validator.addMethod(
  "SpecCharValidate",
  function (value, element) {
    var characterReg = /^\s*[a-zA-Z,\s]+\s*$/;
    if (!characterReg.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "No Special Chars or Numbers Allowed in the City Name"
);
$.validator.addMethod(
  "validEmail",
  function (value, element) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if (!regex.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid Email!"
);
// For facebook url valiation
$.validator.addMethod(
  "validFBurl",
  function (value, element) {
    var FBurl = /^(http|https)\:\/\/facebook.com\/.*/;
    if (!FBurl.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid FB!"
);
$.validator.addMethod(
  "validTwitterUrl",
  function (value, element) {
    var Twitterurl = /https?:\/\/twitter\.com\/(#!\/)?[a-z0-9_]+$/i;
    if (!Twitterurl.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid Twitter !"
);
$.validator.addMethod(
  "validInstagramUrl",
  function (value, element) {
    var Instaurl = /^\s*(http\:\/\/)?instagram\.com\/[a-z\d-_]{1,255}\s*$/i;
    if (!Instaurl.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid Instagram !"
);
$.validator.addMethod(
  "validPinterestUrl",
  function (value, element) {
    var Pinteresturl = /^\s*(http\:\/\/)?pinterest\.com\/[a-z\d-_]{1,255}\s*$/i;
    if (!Pinteresturl.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid Pinterest url !"
);
$.validator.addMethod(
  "validLinkedinUrl",
  function (value, element) {
    var Linkedinurl = /^\s*(http\:\/\/)?linkedin\.com\/[a-z\d-_]{1,255}\s*$/i;
    if (!Linkedinurl.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid Linkedin url !"
);
$.validator.addMethod(
  "validYoutubeUrl",
  function (value, element) {
    var Youtubeurl = /^\s*(http\:\/\/)?youtube\.com\/[a-z\d-_]{1,255}\s*$/i;
    if (!Youtubeurl.test(value)) {
      return false;
    } else {
      return true;
    }
  },
  "Enter the valid Linkedin url !"
);
function updatePayment(invoiceId) {
  console.log(invoiceId);
  $.ajax({
    url: base_url + "ajax/update_Payment", // URL to the direct PHP script
    type: "POST",
    data: {
      id: invoiceId,
    },
    dataType: "json",
    success: function (response) {
      console.log("Response:", response);
      if (response.status === "success") {
        alert(response.message);
        // window.location.href = '<?php echo base_url(); ?>invoice';
        //return false;
      } else {
        alert(response.message);
        //return false;
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
      //return false;
    },
  });
}

function show_appoinments_datechange(Appointment_id, from, to) {
  console.log(Appointment_id);
  console.log(from);
  console.log(to);

  $.ajax({
    url: base_url + "ajax/update_Appointment", // URL to the direct PHP script
    type: "POST",
    data: {
      id: Appointment_id,
      from: from,
      to: to,
    },
    dataType: "json",
    success: function (response) {
      console.log("Response:", response);
      if (response.status === "success") {
        alert(response.message);
        window.location.href = base_url + "doctors-search";
        //return false;
      } else {
        alert(response.message);
        //return false;
      }
    },
    error: function (xhr, status, error) {
      console.error("Error:", error);
      //return false;
    },
  });
}
function pharmacy_reg_payment(type) {
  // var terms_accept=$("input[name='terms_accept']:checked").val();
  var terms_accept = 1;
  if (terms_accept == "1") {
    if (type == "paypal") {
      $("#pay_buttons").attr("disabled", true);
      $("#pay_buttons").html(
        '<div class="spinner-border text-light" role="status"></div>'
      );
      $("#payment-form").attr("action", base_url + "cart/paypal_pay");
      $("#payment-form").submit();
    } else if (type == "Cybersource") {
      console.log("dsa");
      $("#payment_reg_confirmation").submit();
      //return false;
    } else {
      var payment_method = $("input[name='payment_methods']:checked").val();
      if (payment_method != "Card Payment") {
        $("#my_book_appoinment").click();
      }

      return false;
    }
  } else {
    toastr.warning(lg_please_accept_t);
  }
}
if (pages == "register") {
  console.log("object");
  function handleRoleChange(role) {
    // Hide all role-specific sections
    document
      .querySelectorAll(
        ".patient_role_2, .doctor_role_1,.lab_role_4,.pharmacy_role_5,.clinic_role_6"
      )
      .forEach(function (div) {
        div.style.display = "none";
      });

    // Remove 'active' class from all buttons
    document.querySelectorAll("button").forEach(function (btn) {
      btn.classList.remove("active");
    });

    // Show the relevant section based on the selected role
    if (role === 2) {
      document.querySelector(".patient_role_2").style.display = "block";
      document.getElementById("pat_btn").classList.add("active");
    } else if (role === 1) {
      document.querySelector(".doctor_role_1").style.display = "block";
      document.getElementById("doc_btn").classList.add("active");
    } else if (role === 4) {
      document.querySelector(".lab_role_4").style.display = "block";
      document.getElementById("lab_btn").classList.add("active");
    } else if (role === 5) {
      document.querySelector(".pharmacy_role_5").style.display = "block";
      document.getElementById("pha_btn").classList.add("active");
    } else {
      document.querySelector(".clinic_role_6").style.display = "block";
      document.getElementById("cli_btn").classList.add("active");
    }

    // Call the change_role function to send the role change
    change_role(role);
  }
  handleRoleChange(2);
}

// function add_team_member(){
//   console.log("add_team_member");
// }