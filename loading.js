document.getElementsByTagName("form")[0].id="login";
var login = document.getElementById("login")
    document.getElementById("login").onsubmit = function loader(){
        
        login.style.display="none";
        document.getElementById("login-form").innerHTML = '<br><br><br><br><br><iframe src="data:text/html;base64,PCFET0NUWVBFIGh0bWw+DQo8aHRtbCBsYW5nPSJlbiI+DQo8aGVhZD4NCiAgICA8bWV0YSBjaGFyc2V0PSJVVEYtOCI+DQogICAgPG1ldGEgbmFtZT0idmlld3BvcnQiIGNvbnRlbnQ9IndpZHRoPWRldmljZS13aWR0aCwgaW5pdGlhbC1zY2FsZT0xLjAiPg0KICAgIDx0aXRsZT5Eb2N1bWVudDwvdGl0bGU+DQo8c3R5bGU+DQogICAgKnsNCiAgICAgICAgb3ZlcmZsb3c6IGhpZGRlbjsNCiAgICB9DQouc3Bpbm5lci1wYXRoew0KICAgIGFuaW1hdGlvbjogMS41cyBkYXNoIGluZmluaXRlOw0KICAgIHN0cm9rZTojMGQ2ZWZkOw0KICAgIHRyYW5zaXRpb246IDFzOw0KfQ0KLnNwaW5uZXItY29udGFpbmVyew0KICAgICAgICB6LWluZGV4OiAyOw0KICAgICAgICBhbmltYXRpb246IHJvdGF0ZSAycyBsaW5lYXIgaW5maW5pdGU7DQogICAgICAgDQp9DQpAa2V5ZnJhbWVzIGRhc2h7DQogICAgMCV7DQogICAgICAgIHN0cm9rZS1kYXNoYXJyYXk6IDEsMTUwOw0KICAgICAgICBzdHJva2UtZGFzaG9mZnNldDogMDsNCg0KICAgIH0NCiAgICA1MCV7DQogICAgICAgIHN0cm9rZS1kYXNoYXJyYXk6IDEwMCwxNTA7DQogICAgICAgIHN0cm9rZS1kYXNob2Zmc2V0OiAtMzU7DQoNCiAgICB9DQogICAgMTAwJXsNCiAgICAgICAgc3Ryb2tlLWRhc2hhcnJheTogMTAwLDE1MDsNCiAgICAgICAgc3Ryb2tlLWRhc2hvZmZzZXQ6IC0xMjQ7DQoNCiAgICB9DQp9DQpAa2V5ZnJhbWVzIHJvdGF0ZXsNCiAgIDEwMCUgeyANCiAgICAgICB0cmFuc2Zvcm06IHJvdGF0ZSgzNjBkZWcpOyANCiAgICAgICB9DQp9DQo8L3N0eWxlPg0KPC9oZWFkPg0KICAgIDxib2R5Pg0KICAgICAgICAgICAgPHN2ZyBjbGFzcz0ic3Bpbm5lci1jb250YWluZXIiIGhlaWdodD0iNTAlIiB3aWR0aD0iNTAlIiB2aWV3Qm94PSIwIDAgNDQgNDQiPg0KICAgICAgICAgICAgDQogICAgICAgICAgICA8Y2lyY2xlIGNsYXNzPSJzcGlubmVyLXBhdGgiIGN4PSIyMiIgY3k9IjIyIiByPSIyMCIgc3Ryb2tlLXdpZHRoPSIyIiBmaWxsPSJub25lIj48L2NpcmNsZT48L3N2Zz4NCiAgICA8L2JvZHk+DQogICAgPC9odG1sPg==" frameborder="0" oncontextmenu="return false;"></iframe>';
        var style = document.getElementsByTagName("style")[0];
        var textnode = document.createTextNode("#login-form{display: flex;justify-content: center;margin-top: 20%;         margin-left: 12%;}");
        style.appendChild(textnode);
        
    }
    