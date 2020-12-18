    jQuery(document).ready(function ($) {

        $('form[id="form"]').validate({
            rules: {
                name: 'required',
                city: 'required',
                street_name: 'required',
                postcode: 'required',
                phone_number: {
                    required: true,
                    minlength: 9,
                    digits: true
                },
                shop_list: 'required',
                  max_money: {
              required: true,
                digits: true,
              },
            },
            messages: {
                name: 'Proszę wpisać swoje imię',
                city: 'Proszę wpisać nazwę miasta',
                street_name: 'Proszę wpisać nazwę ulicy.',
                postcode: 'Proszę wpisać kod pocztowy',
                phone_number: {
                    required: "Proszę wpisać numer telefonu",
                    minlength: 'Numer telefonu musi się składać z minimum 9 cyfr',
                    digits: "Numer telefonu musi składać się z minimum 9 cyfr",
                },
                shop_list: 'Proszę wpisać potrzebne produkty',
                max_money: {
                  required: 'Proszę wpisać maksymalną kwotę na zakupy',
                    digits: 'Wpisz liczbę',
              },

            },
            submitHandler: function (form) {
                form.submit();
            }
        });

    });