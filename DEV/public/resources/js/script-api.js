window.onload = function(){};



    var password = 'test';
    //var url      = 'http://sandbox.sherfi.net/julien/framework/DEV/';
    var url      = 'http://localhost:8888/Projets/framework/mode-simple/DEV/';

    function TestMethod() {

        complement	= $('#method').val();
		urlAjax = url+complement;

        if (urlAjax.indexOf('{objet}') >= 0)
        {
            if ($('#objet').val() + '' == '')
            {
                alert('vous devez insérer le nom d\'un OBJET');
                return;
            }else{
                urlAjax = urlAjax.replace('{objet}', $('#objet').val());
            }
        }

        if (urlAjax.indexOf('{id}') >= 0)
        {
            if ($('#id').val() + '' == '')
            {
                alert('vous devez insérer un ID');
                return;
            }else{
                urlAjax = urlAjax.replace('{id}', $('#id').val());
            }
		}

        if (urlAjax.indexOf('{code}') >= 0)
        {
            if ($('#code').val() + '' == '')
            {
                alert('vous devez insérer un Code');
                return;
            }else{
                urlAjax = urlAjax.replace('{code}', $('#code').val());
            }
		}

        $.ajax({ url: urlAjax+'.json', dataType: 'json', type: 'POST', data: {password: password},

            success: function (data) {
                    $('#result').val(JSON.stringify(data));
            },

            error: function (xhr, ajaxOptions, thrownError) {
                $('#result').val('OUPS ! :' + thrownError);
            }

        });

    }


