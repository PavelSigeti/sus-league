<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//RU">

<h3>Feedback #{{$feedback->id}} -{{$feedback->title}}</h3>

<table border="0" style="margin:0; padding:0">
    <tr>
        <td>Имя:</td>
        <td>{{$user->name}} {{$user->surname}} ({{$user->nickname}})</td>
    </tr>
    <tr>
        <td>Темa:</td>
        <td>{{$feedback->title}}</td>
    </tr>
    <tr>
        <td>Сообщение:</td>
        <td>{{$feedback->text}}</td>
    </tr>
</table>
