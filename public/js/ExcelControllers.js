/**
 * Chinese Name Get Pinyin
 */
function getPinyin() {

    // 验证规则
    $('#one').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            excel: {
                message: '文件上传是否为空',
                validators: {
                    notEmpty: {
                        message: '必须上传文件！'
                    }
                }
            },
        }
    });

    var formData = new FormData();
    if ($("#one").data("bootstrapValidator").isValid()) {
        formData.append('excel', $('#exampleInputFile')[0].files[0]);
        $.ajax({
            url: apiUrl + "/import-excel-get-py",
            data: formData,
            dataType: "JSON",
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.code) {
                    window.location.href = result.message;
                } else {
                    alert(result.message);
                }
            },
        });
    } else {
        alert('No Files');
    }

}

/**
 * Rewrite Image's Name
 */
function nameImage() {

    // 验证规则
    $('#two').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            commonName: {
                message: '前缀名称',
                validators: {
                    notEmpty: {
                        message: '前缀名称不可为空！'
                    }
                }
            },
            excelFile: {
                message: '文件上传是否为空',
                validators: {
                    notEmpty: {
                        message: '必须上传文件！'
                    }
                }
            },
            imageFile: {
                message: '图片上传是否为空',
                validators: {
                    notEmpty: {
                        message: '必须上传图片！'
                    }
                }
            },
        }
    });


    var formData = new FormData();
    if ($("#two").data("bootstrapValidator").isValid()) {
        var imageData = $('#imageFile')[0].files;
        formData.append('common', $('#commonName').val());
        formData.append('excel', $('#excelFile')[0].files[0]);
        for (var i = 0; i < imageData.length; i++) {
            formData.append('img[]', imageData[i]);
        }
        $.ajax({
            url: apiUrl + "/img-change-name",
            data: formData,
            dataType: "JSON",
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (result) {
                if (result.code) {
                    window.location.href = result.message;
                } else {
                    alert(result.message);
                }
            },
        });
    } else {
        alert('No Files Or Image!');
    }

}


