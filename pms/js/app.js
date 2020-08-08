// ---PRELOADING---
$(window).load(function () {
    $("#preLoadOverlayer,#preloadContainer").delay(2000).fadeOut("slow");
}); // ---PRELOADING---

/// check string is json, if this data is json and param=true then return js object ///
var jsonToObj; /// json to js object converted data container ///
function is_json(stringData, getObject) {
    try {
        jsonToObj = JSON.parse(stringData);
        setTimeout(function () {
            jsonToObj = undefined;
        }, 1000);
        if (getObject && jsonToObj) {
            return jsonToObj;
        }
    } catch (e) {
        return false;
    }
    return true;
}

function dirname(link, limit) {
    if (limit > 0) {
        for (var i = 0; i <= limit; i++) {
            link = link.substring(0, link.lastIndexOf('/'));
        }
    } else {
        link = link.substring(0, link.lastIndexOf('/'));
    }
    return link;
}

function formDataToObject(formData) {
    var data = {};
    if (typeof formData === 'object') {
        formData.forEach(function (value, name) {
            data[name] = value;
        });
    }
    return data;
}

function currentPage(link) {
    return link.substring(link.lastIndexOf('/'), link.length);
}

$(document).ready(function () {
    var messageShow = $('input[name="messageShow"], input[name="errorShow"]').val();
    if (messageShow === "0") {
        setTimeout(function () {
            $(".alert, .error_ms").fadeOut();
        }, 3000);
    } else if (messageShow === "1") {
        setTimeout(function () {
            $(".alert, .error_ms").fadeOut();
        }, 6000);
    } else if (messageShow === "2") {
        setTimeout(function () {
            $(".alert, .error_ms").fadeOut();
        }, 10000);
    }
});
$(document).ready(function () {
    $(".menu > .list > li").click(function () {
        $(this).toggleClass("active");
    });
    $(".ml-menu li").click(function () {
        $(this).toggleClass("active");
    });
});
$(document).ready(function () {
    $('.check-div').css({
        width: '1200px',
        float: 'left',
        position: 'absolute',
        left: '15%'
    });
    $(document).on('click', 'input[name="currency"]', function () {
        $('.check_er').fadeOut(100);
        $('.submit').removeClass('clickDisabled');
    });
    $(document).on('keyup', 'input[name="amount"]', function () {
        var currency = $('input[name="currency"]:checked').val();
        if (currency === undefined) {
            $('.check_er').fadeIn(100);
            $('.submit').addClass('clickDisabled');
        } else {
            $('.check_er').fadeOut(100);
            $('.submit').removeClass('clickDisabled');
        }
    });
    var letters = /^[a-zA-Z\s]+$/;
    var numbers = /^[-+]?[0-9]+$/;
    $(document).on('keyup', '.num_valid', function (e) {
        e.preventDefault();
        if (this.value.match(numbers)) {
            $('.number_er').fadeOut(100);
            $('.submit').removeClass('clickDisabled');
        } else if ($(this).val().length > 0) {
            $('.number_er').fadeIn(100);
            $('.submit').addClass('clickDisabled');
        }
    });
    $(document).on('keyup', '.alpha_valid', function (e) {
        e.preventDefault();
        if (this.value.match(letters)) {
            $('.alphabet_er').fadeOut(100);
        } else if ($(this).val().length > 0) {
            $('.alphabet_er').fadeIn(100);
        }
    });
});


$(function () {
    $('[data-toggle="datepicker"]').datepicker({
        format: "mm-dd-yyyy",
        autoHide: true
    });
});



// ----------- pages scripts ---------------

var base_url = $('#base_url').data('base_url');
var currency = $('#currency').data('currency');
var thisPage = window.location.href.split('/');
thisPage = thisPage[(thisPage.length - 1)];
thisPage = thisPage.split('?');
thisPage = thisPage[0];

// Earngin details ajax reload script
$(document).ready(function () {
    $(document).on('change', '#month', function () {
        var month = $('#month').val();
        if (month !== null && thisPage === 'earning_details') {
            $('.check_er').fadeOut(100);
            $.ajax({
                url: base_url + 'earning_details_reloader',
                type: "POST",
                data: ({
                    month: month
                }),
                success: function (data) {
                    if (data.length > 0) {
                        $('#earning_details_tbody').html(data);
                        var countData = $('input[name="countData"]').val().split(',');
                        var totalEarn = parseInt(countData[0]);
                        var totalExpence = parseInt(countData[1]);
                        var currencyVal = currency;
                        $('#totalEarn').html('Total earn : ' + totalEarn + ' ' + currencyVal);
                        $('#balance').html('Balance : ' + (totalEarn - totalExpence) + ' ' + currencyVal);
                        $('#countDetails').html('( ' + totalEarn + ' - ' + totalExpence + ' = ' + (totalEarn - totalExpence) + ')');
                    } else {
                        $('#totalEarn').html('Total earn : 0');
                        $('#balance').html('Balance : ' + (0 - 0) + ' ' + 0);
                        $('#countDetails').html('( ' + 0 + ' - ' + 0 + ' = ' + (0 - 0) + ')');
                        $('#earning_details_tbody').html('<tr><td colspan="6" class="notFound">There is no data abailable in here.</td></tr>');
                        $('.total-cc h2').html('Total cost : ');
                    }
                }
            });
        } else {
            $('.check_er').fadeIn(100);
        }
    });
});


// Earning list ajax reload script
$(document).ready(function () {
    function earn_details_table() {
        $('#earn_details_table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
                    }
                }
            ]
        });
    }
    thisPage === 'earning_list' ? earn_details_table() : false;

    // to reload data by changing month
    $(document).on('change', '#month', function () {
        var month = $('#month').val();
        if (month !== null && thisPage === 'earning_list') {
            $('#earn_details_tbody').html('');
            $('#earn_details_table').DataTable().destroy();
            $.ajax({
                url: base_url + 'earning_list_reloader',
                type: "POST",
                data: ({
                    month: month
                }),
                success: function (data) {
                    if (data.length > 0) {
                        $('#earn_details_tbody').html(data);
                        return earn_details_table();
                    } else {
                        $('#earn_details_tbody').html('<tr><td colspan="6" style="text-align:center">There is no data abailable in here.</td></tr>');
                    }
                }
            });
        }
    });
});



//  Expenses manage page scripts
$(document).ready(function () {
    var letters = /^[a-zA-Z\s]+$/;
    $(document).on('click', '.clickChecker', function () {
        var classes = $('.clickDisabled').attr('class');
        if (classes) {
            classes = classes.split(' ');
            if (classes.includes('clickDisabled')) {
                var rowCheck = $('#costDa tr').length;
                if (rowCheck === 0) {
                    alert('Please add expenses');
                } else if (rowCheck > 0) {
                    alert('Please filling all input fields');
                }
            }
        }
    });
    $(document).on('click', '#plus', function () {
        var $inputs = $('form #costDa').find(':text').filter(function () {
            return $.trim(this.value) != '';
        });
        var countNo = $('#costDa tr > td > :text').length;
        var tind = $('#costDa tr').length;
        if (countNo == $inputs.length) {
            $('#plus, #submit').addClass('clickDisabled');
            $('#costDa').append('<tr class="input-co"><td><b>' + (tind + 1) + '</b></td><td><input type="text" class="form-control" name="costCn[]"></td>' +
                '<td><input type="text" class="form-control" name="costCa[]"></td>' +
                '<td><input type="file" name="memo[]" multiple ><span class="icon-c" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Remove this row"><i class="material-icons done-i">done</i> <i class="material-icons delete-i">delete</i></span></td></tr>');
            $('#message').fadeOut(100);
        }
    });
    $(document).on('click', '#costDa tr > td > span > i', function () {
        $('#plus, #submit').removeClass('clickDisabled');
        $(this).parent().parent().parent().remove();
        return getData();
    });

    function getData() {
        var amountData = [],
            total = 0;
        $.each($('input[name="costCa[]"]'), function () {
            amountData.push($(this).val());
            $('#amountD').val(amountData);
        });

        if (amountData.length > 0) {
            total = amountData.reduce(function (a, b) {
                return parseInt(a) + parseInt(b);
            });
        } else {
            total = 0;
        }

        $('input[name="amount"]').val(total);
        $('#totalAdd').html('<span>Total = </span>' + total + ' ' + currency);
    }
    $(document).on('keyup', '#costDa td input', function () {
        return getData();
    });

    $(document).on('keyup', 'input[name="costCn[]"]', function (e) {
        e.preventDefault();
        var inputString = $(this).val();
        var spaceCount = (inputString.split(" ").length - 1);
        if (($(this).val().length < 4) || (spaceCount < 1)) {
            if (this.value.match(letters)) {
                $('#message').fadeOut(100);
                $('#plus, #submit').removeClass('clickDisabled');
            } else if ($(this).val().length > 0) {
                $('input[name="amount"]').val('');
                $('#plus, #submit').addClass('clickDisabled');
                $('#message').html("<div class='alert alert-danger'><h4><b>Sorry! </b> Please input valid Name with Alphabet.your Input is <b>" + this.value + "</b></h4></div>").fadeIn(200);
            }
        } else {
            $('#message').fadeOut(100);
            $('#addAction').fadeIn(150);
        }
    });
    $(document).on('keyup', 'input[name="costCa[]"]', function (e) {
        e.preventDefault();
        if (!isNaN(this.value)) {
            $('#message').fadeOut(100);
            $('#plus, #submit').removeClass('clickDisabled');
        } else if (this.value.length > 0) {
            $('input[name="amount"]').val('');
            $('#plus, #submit').addClass('clickDisabled');
            $('#message').html("<div class='alert alert-danger'><h4><b>Sorry! </b> Please input valit <b>Number</b>.</h4></div>").fadeIn(200);
        }
    });
});



// Expenses details data ajax reload script
$(document).ready(function () {
    $(document).on('change', '#month', function () {
        var month = $('#month').val();
        if (thisPage === 'expensesDetails' && month !== null) {
            $.ajax({
                url: base_url + 'expenses_details_reloader',
                type: "POST",
                data: ({
                    month: month
                }),
                success: function (data) {
                    if (data.length > 0) {
                        $('#expenses_details_tbody').html(data);
                        $('.total-cc h2').html($('input[name="total"]').val());
                    } else {
                        $('#expenses_details_tbody').html('<tr><td colspan="6" class="notFound">There is no data abailable in here.</td></tr>');
                        $('.total-cc h2').html('Total expense : 0');
                    }
                }
            });
        }
    });
});



// Expenses list ajax reload script
$(document).ready(function () {
    function table() {
        $('#expenses_list_table').DataTable({
            dom: 'Bfrtip',
            responsive: true,
            buttons: [{
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                }
            ]
        });
    }

    thisPage === 'expenses_list' ? table() : false;

    $(document).on('change', '#month', function () {
        var month = $('#month').val();
        if (month !== null && thisPage === 'expenses_list') {
            $('#expenses_list_tbody').html('');
            $('#expenses_list_table').DataTable().destroy();
            $.ajax({
                url: base_url + 'expenses_list_reloader', // expense relode by month
                type: "POST",
                data: ({
                    month: month
                }),
                success: function (data) {
                    if (data.length > 0) {
                        $('#expenses_list_tbody').html(data);
                        return table();
                    } else {
                        $('#expenses_list_tbody').html('<tr><td colspan="6" class="notFound">There is no data abailable in here.</td></tr>');
                    }
                }
            });
        }
    });
});



// script for forgot password page
$(document).ready(function () {
    $(document).on('click', '.forgotChecker', function () {
        var actionFor = $(this).text();
        if (actionFor.length === 13) {
            $('#forgot_a').slideDown();
            $('#forgot_e').slideUp();
        } else {
            $('#forgot_e').slideDown();
            $('#forgot_a').slideUp();
        }
    });
});



//  Hom page script. finance chart script
$(document).ready(function () {
    if (thisPage === 'index') {
        var day = new Date();
        var year = day.getFullYear(); // current year
        Chart.defaults.global.defaultFontColor = '#333';
        Chart.defaults.global.defaultFontSize = 16;
        var barChartData = {
            labels: chartData['chartLabelData'],
            datasets: [{
                    label: 'Earning',
                    backgroundColor: "rgba(54, 162, 235, 0.5)",
                    borderColor: "rgb(54, 162, 235)",
                    borderWidth: 1,
                    data: chartData['chartData']['earn'] // earn data
                },
                {
                    label: 'Expences',
                    backgroundColor: "rgba(255, 99, 132, 0.5)",
                    borderColor: "rgb(255, 99, 132)",
                    borderWidth: 1,
                    data: chartData['chartData']['cost'] // expense data
                }
            ]

        };

        window.onload = function () {
            var ctx = document.getElementById('barCanvas').getContext('2d'); //  custo font siz
            window.myBar = new Chart(ctx, {
                type: 'bar',
                data: barChartData,
                options: {
                    responsive: true,
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Financial Graph ' + year
                    }
                }
            });

        };
    }
});



// Company settings page manage script
$(document).ready(function () {
    if (thisPage === 'manageSettings') {
        $(document).on('click', '.clickCheck', function () {
            if ($(this).text() === 'Close') {
                $('.editContainer').hide();
                $('.detailsContainer').fadeIn(1000);
            } else {
                $('.editContainer').fadeIn(1000);
                $('.detailsContainer').hide();
            }
        });
        $(document).on('click', '.waves-effect', function () {
            $('.wizard > .actions li:last-child a').text('Update').css('background', '#ff9800');
        });
        $(document).on('click', '.wizard > .actions li:last-child a', function () {
            $('#submit').trigger('click');
        });
    }
});




// partner finance amount reloader
$(document).ready(function () {
    function finance_reload_data(data) {
        var symbol = data['symbol']
        $('.ac-head-con').html(data['account_head']);
        $('#totalEarn span').text(data['totalEarn'] + ' ' + symbol);
        $('#totalExpense span').text(data['totalExpense'] + ' ' + symbol);
        $('#balance span').text(data['balance'] + ' ' + symbol);
        $('#countDetails').html(data['countDetails'] + ' ' + symbol);
        $('#my_earn span').text(data['persentageEarn'] + ' ' + symbol);
        $('#my_expense span').text(data['yourExpense'] + ' ' + symbol);
        $('#my_share_expense span').text(data['persentageShare'] + ' ' + symbol);
        $('#revenue span').text(data['revenue'] + ' ' + symbol);
    }
    $(document).on('change', '#month', function () {
        var month = $('#month').val();
        if (month !== null && thisPage === 'my_account_details') {
            $.ajax({
                url: base_url + 'my_account_details_reloader', // expense relode by month
                type: "POST",
                data: ({
                    month: month
                }),
                success: function (data) {
                    return finance_reload_data(JSON.parse(data));
                }
            });
        }
    });
});



// atfirst register partmer page script
$(document).ready(function () {
    $(document).on('keyup', 'input[name="percentage"]', function () {
        var current_val = parseInt($('input[name="persentageCheck"]').val());
        var inputVal = parseInt(this.value);
        if (this.value.length > 1) {
            if (isNaN(inputVal)) {
                this.value = '';
                alert('Invalid percentage amount ' + (current_val + inputVal));
            } else if ((current_val + inputVal) > 100) {
                this.value = '';
                alert('Invalid percentage amount alread taken  ' + current_val + '% + your input value ' + inputVal + '% = ' + (current_val + inputVal) + '% > 100%');
            }
        }
    });
});



// atfirst company settings script
$(document).ready(function () {
    $(document).on('click', '#wizard_with_validation .actions li:last-child a', function () {
        $('#settingsSubmit').trigger('click');
    });

    // check smtp connection error
    var check = $('#settings_error').data('settings_error');
    if (check) {
        setTimeout(function () {
            $('.wizard > .actions ul li:nth-child(2) > a').trigger('click');
        }, 20);
    }
});



// User registration manage script
$(document).ready(function () {
    if (thisPage === 'userRegistration') {
        $(document).on('click', 'input[name="userRoll"]', function () {
            var userRoll = $('input[name="userRoll"]:checked').val();
            $('.percentage input').val('');
            if (userRoll === 'Partner') {
                $('input[name="percentage"]').attr('required', 'required');
                $('.percentage').fadeIn(300);
            } else if (userRoll === 'Manager') {
                $('input[name="percentage"]').removeAttr('required');
                $('.percentage').fadeOut(400);
            }
        });

        // percentage checker
        $(document).on('keyup', 'input[name="percentage"]', function () {
            var inputVal = parseInt(this.value);
            var currentPercentage = parseInt($('#currentPercentage').val());
            if (this.value.length > 0) {
                if (isNaN(inputVal)) {
                    this.value = '';
                    alert('Invalid percentage amount ' + (currentPercentage + inputVal));
                } else if ((currentPercentage + inputVal) > 100) {
                    this.value = '';
                    alert('Invalid percentage amount alread taken  ' + currentPercentage + '% + your input value ' + inputVal + '% = ' + (currentPercentage + inputVal) + '% > 100%');
                }
            }
        });
    }
});
