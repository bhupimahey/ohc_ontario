/*=========================================================================================
  File Name: form-validation.js
  Description: jquery bootstrap validation js
  ----------------------------------------------------------------------------------------
  Item Name: Vuexy  - Vuejs, HTML & Laravel Admin Dashboard Template
  Author: PIXINVENT
  Author URL: http://www.themeforest.net/user/pixinvent
==========================================================================================*/

$(function () {
  'use strict';

  var bootstrapForm = $('.needs-validation'),
    jqForm = $('#jquery-val-form'),
	enquiryForm = $('#enquiry-form'),
	walkingsForm = $('#walkings-form'),
	doctorForm = $('#doctor-form'),
    picker = $('.picker'),
    select = $('.select2');

  // select2
  select.each(function () {
    var $this = $(this);
    $this.wrap('<div class="position-relative"></div>');
    $this
      .select2({
        placeholder: 'Select value',
        dropdownParent: $this.parent()
      })
      .change(function () {
        $(this).valid();
      });
  });

  // Picker
  if (picker.length) {
    picker.flatpickr({
      allowInput: true,
      onReady: function (selectedDates, dateStr, instance) {
        if (instance.isMobile) {
          $(instance.mobileInput).attr('step', null);
        }
      }
    });
  }

  // Bootstrap Validation
  // --------------------------------------------------------------------
  if (bootstrapForm.length) {
    Array.prototype.filter.call(bootstrapForm, function (form) {
      form.addEventListener('submit', function (event) {
        if (form.checkValidity() === false) {
          form.classList.add('invalid');
        }
        form.classList.add('was-validated');
        event.preventDefault();
        // if (inputGroupValidation) {
        //   inputGroupValidation(form);
        // }
      });
      // bootstrapForm.find('input, textarea').on('focusout', function () {
      //   $(this)
      //     .removeClass('is-valid is-invalid')
      //     .addClass(this.checkValidity() ? 'is-valid' : 'is-invalid');
      //   if (inputGroupValidation) {
      //     inputGroupValidation(this);
      //   }
      // });
    });
  }

  // jQuery Validation
  // --------------------------------------------------------------------
  if (enquiryForm.length) {
    enquiryForm.validate({
      rules: {
        'firstName': {
          required: true
        },
        'Age': {
          required: true
        },
        'ReferredDoctorName': {
          required: true
        }        
      }
    });
  }
  
  if (walkingsForm.length) {
    walkingsForm.validate({
      rules: {
        'firstName': {
          required: true
        },
        'motherName': {
          required: true,          
        },
        'motherMobile': {
          required: true		  
        },
        'fatherName': {
          required: true         
        },
        'Age': {
          required: true
        },
        'ReferredDoctorName': {
          required: true
        }, 'enquiry_type': {
          required: true
        }        
      }
    });
  }
  
  
  
    if (doctorForm.length) {
    doctorForm.validate({
      rules: {
        'doctorName': {
          required: true
        },
        'doctorADDRESS': {
          required: true,          
        },
        'doctorMobile': {
          required: true		  
        }      
      }
    });
  }
});
