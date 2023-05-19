<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Veziv </title>
  </head>
  <body>

    <h1>Consultants</h1>
    <table class="table">
    <thead>
        <tr>
        <th scope="col">FirstName</th>
        <th scope="col">LastName</th>
        <th scope="col">Appointments</th>
        <th scope="options">Options</th>

        </tr>
    </thead>
    <tbody id='tbody'>
    </tbody>
    <tr>
        <td>  </td>
        <td> Firstname <input type="text" id="ConsultantFirstNameAdd"></td>
        <td> Lastname <input type="text" id="ConsultantLastNameAdd"></td>
        <td> <button id='addNewConsultant'> Add New Consultant </button>
    </tr>
    </table>

    <button id='logout'>Logout</button>

    {{ csrf_field() }}
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src=
"https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js">
    </script>

    <script>
    $(document).ready(function(){
        csrf = $('input[name="_token"]').val()
        var thisUserId = "";

        loadConsultants()

        function loadConsultants(){
            var tbody = $("#tbody")
            tbody.empty()

            token = window.localStorage.getItem('token');
            redirectIfTokenMissingOrExpired()
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "GET", url: "consultant" , success: function(result){

                thisUserId = result['user']['id']

                result['Consultants'].forEach(element => {
                    var tr = $("<tr>");
                    
                    var td = $('<td>')
                    td.html(element.firstName)
                    tr.append(td)
                    
                    var td = $('<td>')
                    td.html(element.lastName)
                    tr.append(td)

                    td = $('<td>')
                    
                        
                    for(var i = 0; i < element['appointments'].length; i++){
                        var ob =$('<span>')
                        ob.html( "At " + element['appointments'][i]['timestamp'] + " with " + element['appointments'][i]['username'] + " .")
                        td.append(ob)
                        var obj = $('<button/>', {
                            target_app_id: element['appointments'][i]['id'],
                            className: 'deleteAppointment',
                            html: 'Delete Appointment'
                        });
                        obj.click(deleteAppointmentfun)
                        td.append(obj)
                        td.append($('<br>'))
                    }

                    tr.append(td)

                    td = $('<td>')
                    var obj = $('<button/>', {
                            target_consultant_id: element['id'],
                            className: 'createNewAppointment',
                            html: 'Create New Appointment'
                        });
                    obj.click(createNewAppointmentfun)
                    td.append(obj)

                    var obj = $('<input/>', {
                            id: 'appointmentDate_' + element['id'],
                            type: 'date'
                        });
                    td.append(obj)


                    var obj = $('<input/>', {
                            id: 'appointmentTime_' +  element['id'],
                            type: 'time'
                        });
                    td.append(obj)
                    
                    var obj = $('<button/>', {
                            target_consultant_id: element['id'],
                            html: 'Remove consultant'
                        });
                    obj.click(deleteConsultantFun)
                    td.append(obj)

                    tr.append(td)
                    tbody.append(tr)
                });
            }});
        }

        $("#addNewConsultant").click(function(){
            token = window.localStorage.getItem('token');
            redirectIfTokenMissingOrExpired()
            result = $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "POST", url: "add_new_consultant", data:{firstname: $('#ConsultantFirstNameAdd').val(), lastname: $('#ConsultantLastNameAdd').val()}, success: function(result){

                if(result['id'] !== 'undefined'){
                    alert("Consultant was saved succesfully.")
                    loadConsultants();
                } else {
                    alert("Error in saving consultant. "  )
                }
            }});
            
        });

        createNewAppointmentfun=function(){
            token = window.localStorage.getItem('token');
            redirectIfTokenMissingOrExpired()
            c_id = $(this).attr('target_consultant_id')
            result = $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "POST", url: "create_new_appointment",
                data:{
                    consultant_id: c_id,
                    date:$('#appointmentDate_' + c_id).val(),
                    time:$('#appointmentTime_' + c_id).val(),
                    user_id: thisUserId
                }, 
                success: function(result){
                     if('error' in result){
                        alert(result['error']);
                     } else {
                        alert("Appointment made");
                        loadConsultants();
                     }

                }});
            
        };

        deleteAppointmentfun =  function(){
            app_id = $(this).attr('target_app_id')
            token = window.localStorage.getItem('token');
            redirectIfTokenMissingOrExpired()
            result = $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "POST", url: "delete_appointment",
                data:{
                    appointment_id: app_id,
                }, 
                success: function(result){
                    alert("Appointment removed.")
                    loadConsultants();
                }});
            
        }

        deleteConsultantFun = function(){
            c_id = $(this).attr('target_consultant_id')
            token = window.localStorage.getItem('token');
            redirectIfTokenMissingOrExpired()
            result = $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "POST", url: "delete_consultant",
                data:{
                    consultant_id: c_id,
                }, 
                success: function(result){
                    loadConsultants();
                }});
            
        };

        $("#logout").click(function(){
            token = window.localStorage.getItem('token');
            result = $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "POST", url: "logout", data:{}, success: function(result){

                alert(result.message)
                window.localStorage.setItem('token', null);
                window.location.href = "/loginform";
            }}); 
        });

        function redirectIfTokenMissingOrExpired(){
            token = window.localStorage.getItem('token');
            if(token == null){
                window.location.href = "/loginform";
                return
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Authorization': 'Bearer ' + token
                },
                type: "GET", url: "consultant" , error: function(data) {
                    if(data['status'] == 401){
                        window.localStorage.setItem('token', null);
                        window.location.href = "/loginform";
                    }
                }})
            
        }
    });
    </script>
</body>
</html>