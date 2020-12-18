// dodaj nowego użytkownika


jQuery(document).ready(function ($) {
    $(document).on('click', '.btntake', function (e) {
        e.preventDefault();
        var item_id = $('.take_nr').val();
        var login = $('#userlogin').val();
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'take_item',
                numer_zlec: item_id,
                userlogin: login,
            },
            success: function (response) {
                alert('Zgłoszenie zostało przeniesione do Twoich zleceń.');
                location.reload();

            },
            error: (data) => {
                console.log('Sorry');
            }
        })
    })

    $(document).on('click', '.btnreturn', function (e) {
        e.preventDefault();
        var item_id = $('.return-item').val();
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'return_item',
                nr_return: item_id,
            },
            success: function (response) {
                alert('Zgłoszenie zostało zwrócone do bazy.');

                location.reload();

            },
            error: (data) => {
                console.log('Sorry');
            }
        })
    })


    $(document).on('click', '.btnfinish', function (e) {
        e.preventDefault();
        var item_id = $('.finished-item').val();
        var login =$('#login').val();
        $.ajax({
            type: 'POST',
            url: ajax_object.ajax_url,
            data: {
                action: 'finished_item',
                nr_finished: item_id,
                login: login,
            },
            success: function (response) {
                alert('Zgłoszenie zostało przeniesione do wykonanych zleceń');
                location.reload();
            },
            error: (data) => {
                console.log('Sorry');


            }
        })
    });

    $(document).on('click', '#btnSubmit', function (e) {
        e.preventDefault();

        var first_name = $('#firstname').val();
        var last_name = $('#lastname').val();
        var phone_number = $('#phone_number').val();
        var city = $('#city').val();
        var postcode = $('#postcode').val();
        var street_name = $('#street_name').val();
        var home_number = $('#home_number').val();
        var shop_list = $('#shop_list').val();
        var max_money = $('#max_money').val();
        var extra_info = $('#extra_info').val();


        if (first_name == "" || street_name == "" || city == "" || shop_list == "") {
            $('#message').html('Proszę uzupełnić wszystkie miejsca');
        } else {
            $.ajax({
                type: 'POST',
                url: ajax_object.ajax_url,
                data: {
                    action: 'set_form',
                    firstname: first_name,
                    lastname: last_name,
                    phone_number: phone_number,
                    street_name: street_name,
                    home_number: home_number,
                    postcode: postcode,
                    city: city,
                    shop_list: shop_list,
                    max_money: max_money,
                    extra_info: extra_info,
                },
                success: function (data) {
                    console.log(data)
                    console.log('congrats, you made it ');
                    $('#message').html(`<h3>Dziękujemy Formularz został wysłany</h3>
                   <div>
                   <table style="padding: 10px">
                   <tr>
                   <td>Zgłaszający</td>
                   <td>${first_name} ${last_name}</td>
                   </tr>
                   <tr>
                   <td>Telefon</td>
                   <td>${phone_number}</td>
                   </tr>
                   <tr>
                   <td>Adres</td>
                   <td>${street_name} ${home_number} ${city} ${postcode}</td>
                   </tr>
                   <tr>
                   <td>Lista Zakupów</td>
                   <td>${shop_list}</td>
                   </tr>
                   <tr>
                   <td>Kwota maksymalna</td>
                   <td>${max_money}</td>
                   </tr>
                   <tr>
                   <td>Uwagi</td>
                   <td>${extra_info}</td>
                   </tr>
                   </table>
                   </div>
                   `);

                    $('#form').trigger('reset');
                },
                error: (data) => {
                    console.log('Sorry');
                    console.log(data);
                    $('#message').html('Problem z połączeniem, przepraszamy spróbuj ponownie później');
                }
            })
        }
    })
})