<?php


if(isset($_POST['chatweesubmit'])){
	
	update_option('chatwee',stripslashes($_POST['chatweesnippet']));
	$chatwee_settings_page_group = $_POST['chatwee-settings-group'];
	
	update_option('chatwee-settings-group[is_for_users]',$chatwee_settings_page_group['is_for_users']);
	update_option('chatwee-settings-group[is_home]',$chatwee_settings_page_group['is_home']);
	update_option('chatwee-settings-group[is_archive]',$chatwee_settings_page_group['is_archive']);
	update_option('chatwee-settings-group[is_search]',$chatwee_settings_page_group['is_search']);
	update_option('chatwee-settings-group[is_page]',$chatwee_settings_page_group['is_page']);
	update_option('chatwee-settings-group[is_single]',$chatwee_settings_page_group['is_single']);
	update_option('chatwee-settings-group[ssostatus]',$chatwee_settings_page_group['ssostatus']);
	update_option('chatwee-settings-group[keyapi]',$chatwee_settings_page_group['keyapi']);
	update_option('chatwee-settings-group[clientid]',$chatwee_settings_page_group['clientid']);
	update_option('chatwee-settings-group[loginallsubdomains]',$chatwee_settings_page_group['loginallsubdomains']);
	update_option('chatwee-settings-group[display_format]',$chatwee_settings_page_group['display_format']);
}
	

?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>WordPress Chat by Chatwee</title>
	
	<style>
	
body{ margin:0; font:normal 12px  "Open Sans", Arial, Helvetica, sans-serif;}

.main-wrapper{ width:100%;}
.cwp-title { font:500 29px  "Open Sans", Arial, Helvetica, sans-serif;margin: 10px 0px; }
.cwp-subtitle { font:normal 13px  "Open Sans", Arial, Helvetica, sans-serif; margin: 0px; }
.cwp-small-title { font:500 19px  "Open Sans", Arial, Helvetica, sans-serif;margin: 7px 0px; }
.cwp-note { padding: 20px 0px; margin: 40px 0px; color: #4bbaf7; border-left: 2px solid #4bbaf7; font: normal 11px  "Open Sans", Arial, Helvetica, sans-serif; background: white; width: 100%; text-indent: 20px; }
.cwp-snippet-wrapper { width:840px;display:inline-block;margin-top:30px;}
	.cwp-instruction-bubble { background: white; padding: 20px; border-radius: 22px; position: relative; float:left; width: 370px; }		
	.cwp-instruction-bubble:after { content: '';position: absolute;border-style: solid;border-width: 9px 0 9px 31px;border-color: transparent #FFFFFF;display: block;width: 0;z-index: 1;right: -31px;top: 21px;}
	.cwp-embed-wrapper {float:right}
		.cwp-embed-wrapper textarea {width:380px; height:120px; border: 2px dashed #ddd; word-wrap:break-word; padding:5px; font-size:12px;resize:none;background:transparent;}
			.cwp-embed-wrapper textarea:focus {background: white;}
.cwp-info li{ list-style-type:circle; margin-left:30px; line-height:20px; color:#000; }
.cwp-info li span{color:#555; font-weight:bold;}
.cwp-btn-wrapper{ margin-top:50px;}

.cwp-register-info { font:normal 15px  "Open Sans", Arial, Helvetica, sans-serif;margin-left:5px;vertical-align:-1px;}

.chatwee-logo
{
	background-image: url('data:image/svg+xml;base64,CjxzdmcgeG1sbnM6c3ZnPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iMTI4Ljk3MDciIGhlaWdodD0iMzkuMTMwMDAxIiB2ZXJzaW9uPSIxLjEiPjxzdHlsZT4uczB7ZmlsbDojMjIyO2ZvbnQtc2l6ZTozNnB4O308L3N0eWxlPjxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKC04MS42ODc1LC00MjIuMzIxNDgpIj48cGF0aCBkPSJtMTExLjA0Mjk3IDQ1MS40NDc1N2MtMC41NzQyNSAwLjI0NjEtMS4xMzA4OSAwLjU3MTMtMS42Njk5MiAwLjk3NTU5LTAuNTM5MDkgMC40MDQzLTEuMDg0MDIgMC44NDY2OC0xLjYzNDc3IDEuMzI3MTUtMC41NTA4MSAwLjQ4MDQ3LTEuMTIyMSAwLjk4NzMtMS43MTM4NyAxLjUyMDUtMC41OTE4MiAwLjUzMzIxLTEuMjMwNDkgMS4wNjA1NS0xLjkxNjAxIDEuNTgyMDMtMC42ODU1NyAwLjUyMTQ5LTEuNDMyNjQgMS4wMTk1NC0yLjI0MTIxIDEuNDk0MTQtMC44MDg2MiAwLjQ3NDYxLTEuNzAyMTcgMC44OTA2My0yLjY4MDY2NyAxLjI0ODA1LTAuOTc4NTMzIDAuMzU3NDItMi4wNTY2NTcgMC42NDE2LTMuMjM0Mzc1IDAuODUyNTQtMS4xNzc3NDkgMC4yMTA5NC0yLjQ4MTQ1OCAwLjMxNjQtMy45MTExMzIgMC4zMTY0MS0xLjU4MjA0Mi0wLjAwMDAxLTMuMDA4Nzk4LTAuMzE5MzQtNC4yODAyNzQtMC45NTgwMS0xLjI3MTQ5MS0wLjYzODY3LTIuMzU4NDA0LTEuNS0zLjI2MDc0Mi0yLjU4Mzk5LTAuOTAyMzQ3LTEuMDgzOTgtMS41OTY2ODMtMi4zNDM3NC0yLjA4MzAwOC0zLjc3OTI5LTAuNDg2MzMtMS40MzU1NC0wLjcyOTQ5NC0yLjk0NDMzLTAuNzI5NDkyLTQuNTI2MzctMC4wMDAwMDItMS4wNzgxMSAwLjExNzE4Ni0yLjIzNTM0IDAuMzUxNTYyLTMuNDcxNjggMC4yMzQzNzMtMS4yMzYzMSAwLjU3MTI4Ny0yLjQ5NjA4IDEuMDEwNzQzLTMuNzc5MyAwLjQzOTQ0OS0xLjI4MzE4IDAuOTc1NTgyLTIuNTYzNDUgMS42MDgzOTgtMy44NDA4MiAwLjYzMjgwNy0xLjI3NzMyIDEuMzUwNTgtMi41MDE5MyAyLjE1MzMyLTMuNjczODIgMC44MDI3MjctMS4xNzE4NSAxLjY4NDU2Mi0yLjI1ODc3IDIuNjQ1NTA4LTMuMjYwNzUgMC45NjA5MjctMS4wMDE5MiAxLjk4NjMxNy0xLjg3NDk3IDMuMDc2MTcyLTIuNjE5MTQgMS4wODk4My0wLjc0NDExIDIuMjM4MjY3LTEuMzI3MTIgMy40NDUzMTMtMS43NDkwMiAxLjIwNzAxNC0wLjQyMTg0IDIuNDYwOTE5LTAuNjMyNzggMy43NjE3MTgtMC42MzI4MSAwLjg0MzcyNiAwLjAwMDAzIDEuNjk5MTk2IDAuMTQ5NDQgMi41NjY0MDYgMC40NDgyNCAwLjg2NzE2IDAuMjk4ODYgMS42NDY0NiAwLjc1ODgyIDIuMzM3ODkgMS4zNzk4OCAwLjY5MTM4IDAuNjIxMTIgMS4yNTY4MSAxLjQwNjI4IDEuNjk2MjkgMi4zNTU0NyAwLjQzOTQzIDAuOTQ5MjUgMC42NTkxNSAyLjA2ODM5IDAuNjU5MTggMy4zNTc0Mi0wLjAwMDAzIDAuODY3MjEtMC4xMzc3MiAxLjc0MzE5LTAuNDEzMDkgMi42Mjc5My0wLjI3NTQxIDAuODg0NzktMC42NTMzNCAxLjczNDQtMS4xMzM3OCAyLjU0ODgzLTAuNDgwNSAwLjgxNDQ3LTEuMDQ1OTMgMS41NzkxMi0xLjY5NjI5IDIuMjkzOTUtMC42NTA0MiAwLjcxNDg2LTEuMzUwNjEgMS4zMzMwMi0yLjEwMDU5IDEuODU0NDktMC43NTAwMiAwLjUyMTUtMS41MjkzMiAwLjkzNDU4LTIuMzM3ODkxIDEuMjM5MjYtMC44MDg2MTIgMC4zMDQ3LTEuNjE3MjA1IDAuNDU3MDQtMi40MjU3ODEgMC40NTcwMy0xLjA2NjQyMiAwLjAwMDAxLTEuODYzMjk2LTAuMTQzNTQtMi4zOTA2MjUtMC40MzA2Ny0wLjUyNzM1OC0wLjI4NzA5LTAuNzkxMDI5LTAuNzAwMTgtMC43OTEwMTYtMS4yMzkyNS0wLjAwMDAxMy0wLjA5MzctMC4wMDI5LTAuMjA1MDctMC4wMDg4LTAuMzMzOTktMC4wMDU5LTAuMTI4ODktMC4wMDg4LTAuMjYzNjYtMC4wMDg4LTAuNDA0My0wLjAwMDAxMy0wLjIxMDkyIDAuMDExNy0wLjQzMDY0IDAuMDM1MTYtMC42NTkxOCAwLjAyMzQyLTAuMjI4NSAwLjA3MDMtMC40MzY1IDAuMTQwNjI1LTAuNjI0MDIgMC4wNzAzLTAuMTg3NDggMC4xNzI4MzctMC4zMzk4MyAwLjMwNzYxNy0wLjQ1NzAzIDAuMTM0NzUxLTAuMTE3MTcgMC4zMTkzMjEtMC4xNzU3NiAwLjU1MzcxMS0wLjE3NTc4IDAuMTQwNjEgMC4wMDAwMiAwLjI2MzY1NyAwLjAyMDUgMC4zNjkxNCAwLjA2MTUgMC4xMDU0NTQgMC4wNDEgMC4yMjI2NDEgMC4wODUgMC4zNTE1NjMgMC4xMzE4NCAwLjEyODg5MSAwLjA0NjkgMC4yODQxNjQgMC4wOTA4IDAuNDY1ODIgMC4xMzE4MyAwLjE4MTYyNSAwLjA0MSAwLjQxMzA3IDAuMDYxNSAwLjY5NDMzNiAwLjA2MTUgMC43NDk5ODMgMC4wMDAwMSAxLjUwODc3MS0wLjE2NDA1IDIuMjc2MzY3LTAuNDkyMTkgMC43Njc1NTktMC4zMjgxMSAxLjQ1ODk2NC0wLjc5MSAyLjA3NDIxNC0xLjM4ODY3IDAuNjE1MjItMC41OTc2NCAxLjExOTEyLTEuMzA5NTUgMS41MTE3Mi0yLjEzNTc0IDAuMzkyNTYtMC44MjYxNSAwLjU4ODg1LTEuNzM3MjkgMC41ODg4Ny0yLjczMzQtMC4wMDAwMi0wLjUyNzMyLTAuMDcwMy0xLjAyODMtMC4yMTA5NC0xLjUwMjkzLTAuMTQwNjUtMC40NzQ1OS0wLjM2OTE2LTAuODkwNi0wLjY4NTU0LTEuMjQ4MDUtMC4zMTY0My0wLjM1NzM5LTAuNzIzNjYtMC42NDQ1LTEuMjIxNjgtMC44NjEzMy0wLjQ5ODA3LTAuMjE2NzctMS4xMDQ1MTUtMC4zMjUxNy0xLjgxOTMzOS0wLjMyNTE5LTAuODQzNzY4IDAuMDAwMDItMS42NjcwMSAwLjE4NDU5LTIuNDY5NzI3IDAuNTUzNzEtMC44MDI3NSAwLjM2OTE3LTEuNTczMjU3IDAuODcwMTQtMi4zMTE1MjMgMS41MDI5My0wLjczODI5NSAwLjYzMjg0LTEuNDM4NDg5IDEuMzgyODMtMi4xMDA1ODYgMi4yNS0wLjY2MjEyMSAwLjg2NzIxLTEuMjc0NDI1IDEuNzk1OTItMS44MzY5MTQgMi43ODYxMy0wLjU2MjUxIDAuOTkwMjUtMS4wNjkzNDUgMi4wMjE1LTEuNTIwNTA4IDMuMDkzNzUtMC40NTExOCAxLjA3MjI4LTAuODM0OTY5IDIuMTQxNjItMS4xNTEzNjcgMy4yMDgwMS0wLjMxNjQxNCAxLjA2NjQyLTAuNTU5NTc3IDIuMTAwNi0wLjcyOTQ5MiAzLjEwMjU0LTAuMTY5OTI5IDEuMDAxOTYtMC4yNTQ4OSAxLjkyNDgxLTAuMjU0ODgzIDIuNzY4NTUtMC4wMDAwMDcgMS4yMDcwNCAwLjE0OTQwNyAyLjI1ODggMC40NDgyNDIgMy4xNTUyOCAwLjI5ODgyMSAwLjg5NjQ4IDAuNzIzNjI1IDEuNjQzNTUgMS4yNzQ0MTQgMi4yNDEyMSAwLjU1MDc3MiAwLjU5NzY1IDEuMjI0NiAxLjA0Mjk3IDIuMDIxNDg1IDEuMzM1OTMgMC43OTY4NjMgMC4yOTI5NyAxLjY5MzM0NyAwLjQzOTQ2IDIuNjg5NDUzIDAuNDM5NDYgMS4yNDIxNzMgMCAyLjM5OTM5OC0wLjE1NTI4IDMuNDcxNjc5LTAuNDY1ODIgMS4wNzIyNDgtMC4zMTA1NSAyLjA5NDcwOC0wLjcxNzc3IDMuMDY3MzgzLTEuMjIxNjggMC45NzI2MzMtMC41MDM5MSAxLjkxMzA2My0xLjA3MjI2IDIuODIxMjkzLTEuNzA1MDggMC45MDgxNy0wLjYzMjgxIDEuODE2MzgtMS4yNjg1NSAyLjcyNDYtMS45MDcyMyAwLjkwODE4LTAuNjM4NjYgMS44MzM5Ni0xLjI0ODA0IDIuNzc3MzUtMS44MjgxMiAwLjk0MzMzLTAuNTgwMDcgMS45NDIzNS0xLjA2OTMzIDIuOTk3MDctMS40Njc3OHpNMTE0LjgyMjI3IDQ0Ni40NTUzOGMwLjQ4MDQ2LTAuNjMyOCAxLjAxOTUyLTEuMzEyNDggMS42MTcxOC0yLjAzOTA2IDAuNTk3NjUtMC43MjY1NSAxLjM5NzQ1LTEuNTY0NDQgMi4zOTk0Mi0yLjUxMzY3IDEuMDAxOTQtMC45NDkyIDEuODIyMjUtMS42MzE4MiAyLjQ2MDkzLTIuMDQ3ODUgMC42Mzg2Ni0wLjQxNiAxLjIzOTI1LTAuNjI0MDEgMS44MDE3Ni0wLjYyNDAzIDAuMTQwNjEgMC4wMDAwMiAwLjI5ODgxIDAuMDI2NCAwLjQ3NDYxIDAuMDc5MSAwLjE3NTc3IDAuMDUyOCAwLjM0Mjc2IDAuMTQ5NDMgMC41MDA5OCAwLjI5MDAzIDAuMTU4MTggMC4xNDA2NSAwLjI5MDAyIDAuMzM2OTQgMC4zOTU1MSAwLjU4ODg3IDAuMTA1NDUgMC4yNTE5NyAwLjE1ODE4IDAuNTc3MTcgMC4xNTgyIDAuOTc1NTktMC4wMDAwMiAwLjc5Njg5LTAuMTExMzUgMS41NzMyNS0wLjMzMzk4IDIuMzI5MS0wLjIyMjY4IDAuNzU1ODctMC42NDQ1NSAxLjY3Mjg2LTEuMjY1NjMgMi43NTA5OC0wLjYyMTExIDEuMDc4MTMtMS4wNDAwNSAyLjAwOTc3LTEuMjU2ODQgMi43OTQ5Mi0wLjIxNjgxIDAuNzg1MTYtMC4zMjUyMSAxLjYwNTQ3LTAuMzI1MTkgMi40NjA5NC0wLjAwMDAyIDAuNDY4NzUgMC4wNzAzIDAuODQzNzUgMC4yMTA5NCAxLjEyNSAwLjE0MDYxIDAuMjgxMjUgMC4zMjIyNSAwLjQyMTg3IDAuNTQ0OTIgMC40MjE4NyAwLjI4MTIzIDAgMC41ODAwNi0wLjA1ODYgMC44OTY0OC0wLjE3NTc4IDAuMzE2MzktMC4xMTcxOCAwLjYzMjgtMC4yNjk1MyAwLjk0OTIyLTAuNDU3MDMgMC4zMTYzOS0wLjE4NzUgMC42MjY5NC0wLjQwMTM2IDAuOTMxNjQtMC42NDE2IDAuMzA0NjctMC4yNDAyMyAwLjU5MTc4LTAuNDg5MjYgMC44NjEzMy0wLjc0NzA3IDAuNjQ0NTEtMC41OTc2NSAxLjI3NzMyLTEuMjc3MzQgMS44OTg0NC0yLjAzOTA3bDAuMjk4ODMgNC42NzU3OGMtMC4yMTA5NiAwLjE2NDA3LTAuNDc3NTYgMC4zNjkxNS0wLjc5OTgxIDAuNjE1MjQtMC4zMjIyOSAwLjI0NjEtMC42Nzk3MSAwLjUwNjg0LTEuMDcyMjYgMC43ODIyMy0wLjM5MjYgMC4yNzUzOS0wLjgxNzQxIDAuNTUzNzEtMS4yNzQ0MiAwLjgzNDk2LTAuNDU3MDUgMC4yODEyNS0wLjkxNzAxIDAuNTMwMjctMS4zNzk4OCAwLjc0NzA3LTAuNDYyOTEgMC4yMTY3OS0wLjkyNTggMC4zOTU1LTEuMzg4NjcgMC41MzYxMy0wLjQ2MjkxIDAuMTQwNjItMC45MDUyOSAwLjIxMDk0LTEuMzI3MTUgMC4yMTA5NC0wLjY0NDU1IDAtMS4yMDQxMi0wLjE3Mjg1LTEuNjc4NzEtMC41MTg1Ni0wLjQ3NDYyLTAuMzQ1Ny0wLjg2NDI3LTAuNzg1MTUtMS4xNjg5NS0xLjMxODM2LTAuMzA0Ny0wLjUzMzItMC41MzMyMS0xLjExNjItMC42ODU1NC0xLjc0OTAyLTAuMTUyMzYtMC42MzI4MS0wLjIyODUzLTEuMjMwNDYtMC4yMjg1Mi0xLjc5Mjk3LTAuMDAwMDEtMC4zNzQ5OSAwLjA0OTgtMC43OTM5NCAwLjE0OTQxLTEuMjU2ODQgMC4wOTk2LTAuNDYyODggMC4yMjI2NS0wLjkyODcgMC4zNjkxNC0xLjM5NzQ2IDAuMTQ2NDgtMC40Njg3NCAwLjMxMDU0LTAuOTI1NzcgMC40OTIxOS0xLjM3MTA5IDAuMTgxNjMtMC40NDUzIDAuMzUxNTUtMC44NDM3NCAwLjUwOTc3LTEuMTk1MzEgMC4xNTgxOS0wLjM1MTU1IDAuMjk1ODgtMC42Mzg2NiAwLjQxMzA4LTAuODYxMzMgMC4xMTcxOC0wLjIyMjY1IDAuMTkzMzUtMC4zNTE1NSAwLjIyODUyLTAuMzg2NzJsLTAuMzY5MTQtMC4wNTI3Yy0wLjE5OTIzIDAuMTI4OTEtMC41MzYxNSAwLjQ1NzA0LTEuMDEwNzQgMC45ODQzNy0wLjQ3NDYyIDAuNTI3MzYtMS4wMTA3NiAxLjE5MjM5LTEuNjA4NCAxLjk5NTEyLTAuNTk3NjcgMC44MDI3NC0xLjIxNTgzIDEuNzA1MDgtMS44NTQ0OSAyLjcwNzAzLTAuNjM4NjggMS4wMDE5Ni0xLjIxNTgzIDIuMDM2MTQtMS43MzE0NSAzLjEwMjU0LTAuMjU3ODIgMC41MjczNS0wLjQ2ODc2IDAuOTg3MzEtMC42MzI4MSAxLjM3OTg4LTAuMTY0MDcgMC4zOTI1OC0wLjMyNTIgMC43MTQ4NS0wLjQ4MzQgMC45NjY4LTAuMTU4MjEgMC4yNTE5NS0wLjMzMTA2IDAuNDQyMzgtMC41MTg1NiAwLjU3MTI5LTAuMTg3NSAwLjEyODktMC40Mjc3MyAwLjE5MzM2LTAuNzIwNyAwLjE5MzM2LTAuMzg2NzIgMC0wLjY5MTQxLTAuMTExMzMtMC45MTQwNi0wLjMzMzk5LTAuMjIyNjYtMC4yMjI2NS0wLjM5MjU4LTAuNTE4NTUtMC41MDk3Ny0wLjg4NzY5LTAuMTE3MTktMC4zNjkxNC0wLjE5MzM2LTAuNzg4MDktMC4yMjg1MS0xLjI1Njg0LTAuMDM1Mi0wLjQ2ODc1LTAuMDUyNy0wLjk0OTIxLTAuMDUyNy0xLjQ0MTQgMC0wLjc4NTE2IDAuMDA5LTEuODEzNDggMC4wMjY0LTMuMDg0OTcgMC4wMTc2LTEuMjcxNDcgMC4wNzMyLTIuNjgwNjUgMC4xNjY5OS00LjIyNzUzIDAuMDkzNy0xLjU0Njg3IDAuMjI4NTItMy4xNzU3NyAwLjQwNDMtNC44ODY3MiAwLjE3NTc4LTEuNzEwOTIgMC40MjQ4LTMuNDAxMzUgMC43NDcwNy01LjA3MTI5IDAuMzIyMjYtMS42Njk5IDAuNzI2NTYtMy4yNjM2NSAxLjIxMjg5LTQuNzgxMjUgMC40ODYzMi0xLjUxNzU1IDEuMDc4MTItMi44NTM0OSAxLjc3NTM5LTQuMDA3ODIgMC42OTcyNi0xLjE1NDI2IDEuNTE0NjQtMi4wNzEyNSAyLjQ1MjE1LTIuNzUwOTcgMC45Mzc0OS0wLjY3OTY2IDIuMDE1NjEtMS4wMTk1IDMuMjM0MzctMS4wMTk1MyAwLjYzMjggMC4wMDAwMyAxLjE1MTM2IDAuMDkzOCAxLjU1NTY3IDAuMjgxMjUgMC40MDQyOCAwLjE4NzUzIDAuNzI2NTUgMC40NTEyIDAuOTY2OCAwLjc5MTAxIDAuMjQwMjEgMC4zMzk4OCAwLjQwNzIxIDAuNzQ3MSAwLjUwMDk3IDEuMjIxNjggMC4wOTM3IDAuNDc0NjQgMC4xNDA2MSAxLjAwNDkyIDAuMTQwNjMgMS41OTA4Mi0wLjAwMDAyIDAuODY3MjItMC4xMjMwNyAxLjgyODE2LTAuMzY5MTQgMi44ODI4Mi0wLjI0NjExIDEuMDU0NzEtMC41NzEzMSAyLjE1MDQxLTAuOTc1NTkgMy4yODcxLTAuNDA0MzEgMS4xMzY3NS0wLjg2NDI3IDIuMjg4MTEtMS4zNzk4OCAzLjQ1NDExLTAuNTE1NjQgMS4xNjYwMy0xLjA0Mjk4IDIuMjk2ODktMS41ODIwMyAzLjM5MjU3LTAuNTM5MDggMS4wOTU3Mi0xLjA2OTM1IDIuMTI5OS0xLjU5MDgyIDMuMTAyNTQtMC41MjE1IDAuOTcyNjctMC45ODE0NiAxLjgyODE0LTEuMzc5ODkgMi41NjY0MXptMS41NjQ0NS0xMC44NjMyOGMwLjY5MTM5LTEuNzkyOTQgMS4xOTIzNy0zLjMwNDY2IDEuNTAyOTMtNC41MzUxNSAwLjMxMDUzLTEuMjMwNDUgMC40NjU4MS0yLjEzODY1IDAuNDY1ODItMi43MjQ2MS0wLjAwMDAxLTAuODkwNi0wLjExNzItMS4zMzU5MS0wLjM1MTU2LTEuMzM1OTQtMC4zMDQ3IDAuMDAwMDMtMC43NTU4NyAwLjQ5ODA4LTEuMzUzNTIgMS40OTQxNC0wLjU5NzY3IDAuOTk2MTItMS4zMDA3OSAyLjQ0MzM5LTIuMTA5MzcgNC4zNDE4LTAuMTk5MjMgMC42NDQ1NS0wLjM4MzggMS4zMTI1Mi0wLjU1MzcyIDIuMDAzOS0wLjE2OTkyIDAuNjkxNDMtMC4zMjIyNyAxLjM3OTkxLTAuNDU3MDMgMi4wNjU0My0wLjEzNDc3IDAuNjg1NTctMC4yNTc4MiAxLjM1OTQtMC4zNjkxNCAyLjAyMTQ5LTAuMTExMzMgMC42NjIxMi0wLjIwODAxIDEuMjkyMDEtMC4yOTAwNCAxLjg4OTY1LTAuMTk5MjIgMS4zOTQ1NC0wLjM1MTU2IDIuNzU5NzctMC40NTcwMyA0LjA5NTcgMC4zMTY0LTAuNzM4MjcgMC42NjIxMS0xLjU0MSAxLjAzNzExLTIuNDA4MiAwLjM3NDk5LTAuODY3MTggMC43NDQxMy0xLjcyNTU3IDEuMTA3NDItMi41NzUyIDAuMzYzMjgtMC44NDk1OSAwLjcwNjA1LTEuNjUyMzIgMS4wMjgzMi0yLjQwODIgMC4zMjIyNi0wLjc1NTg0IDAuNTg4ODYtMS4zOTc0NCAwLjc5OTgxLTEuOTI0ODF6TTE0Ni4wNTg1OSA0NTMuMDgyMzRjLTAuMjY5NTUgMC4zNzUtMC42Mjk5IDAuODE0NDUtMS4wODEwNSAxLjMxODM2LTAuNDUxMTkgMC41MDM5LTAuOTU4MDMgMC45ODQzNy0xLjUyMDUxIDEuNDQxNC0wLjU2MjUyIDAuNDU3MDMtMS4xNjYwMyAwLjg0Mzc1LTEuODEwNTUgMS4xNjAxNi0wLjY0NDU0IDAuMzE2NC0xLjI4OTA3IDAuNDc0NjEtMS45MzM1OSAwLjQ3NDYxLTAuNzczNDUgMC0xLjQxNzk4LTAuMjQ5MDMtMS45MzM1OS0wLjc0NzA3LTAuNTE1NjQtMC40OTgwNS0wLjk2MDk1LTEuMjIxNjgtMS4zMzU5NC0yLjE3MDktMC4yODEyNiAwLjMyODEzLTAuNjI2OTYgMC42NjIxMS0xLjAzNzExIDEuMDAxOTUtMC40MTAxNyAwLjMzOTg1LTAuODU4NDEgMC42NDc0Ni0xLjM0NDczIDAuOTIyODUtMC40ODYzMyAwLjI3NTM5LTAuOTkzMTcgMC41MDA5OC0xLjUyMDUgMC42NzY3Ni0wLjUyNzM1IDAuMTc1NzgtMS4wNTQ3IDAuMjYzNjctMS41ODIwNCAwLjI2MzY3LTAuNTYyNSAwLTEuMTEwMzUtMC4xMTQyNS0xLjY0MzU1LTAuMzQyNzctMC41MzMyMS0wLjIyODUyLTEuMDA0ODktMC41NTY2NC0xLjQxNTA0LTAuOTg0MzgtMC40MTAxNi0wLjQyNzczLTAuNzM4MjgtMC45NDkyMS0wLjk4NDM3LTEuNTY0NDUtMC4yNDYxLTAuNjE1MjMtMC4zNjkxNS0xLjMwOTU2LTAuMzY5MTQtMi4wODMwMS0wLjAwMDAxLTAuODc4OSAwLjEyMzA0LTEuNzgxMjQgMC4zNjkxNC0yLjcwNzAzIDAuMjQ2MDktMC45MjU3NyAwLjYyNjk1LTEuOTI3NzIgMS4xNDI1Ny0zLjAwNTg2IDAuNTE1NjMtMS4wNzgxMSAxLjA2OTM0LTIuMDA5NzUgMS42NjExNC0yLjc5NDkyIDAuNTkxNzktMC43ODUxNCAxLjIzOTI1LTEuNDcwNjkgMS45NDIzOC0yLjA1NjY0IDAuNzAzMTItMC41ODU5MiAxLjQ0NDMzLTEuMDUxNzQgMi4yMjM2My0xLjM5NzQ2IDAuNzc5MjktMC4zNDU2OSAxLjU3MzIzLTAuNTE4NTQgMi4zODE4NC0wLjUxODU1IDAuMjY5NTIgMC4wMDAwMSAwLjQ4MDQ2IDAuMDUyOCAwLjYzMjgxIDAuMTU4MiAwLjE1MjMzIDAuMTA1NDggMC4yNzgzMSAwLjIyNTYgMC4zNzc5MyAwLjM2MDM1IDAuMDk5NiAwLjEzNDc4IDAuMTk2MjggMC4yNjY2MiAwLjI5MDA0IDAuMzk1NTEgMC4wOTM3IDAuMTI4OTIgMC4yMTA5MiAwLjIyMjY3IDAuMzUxNTYgMC4yODEyNSAwLjE0MDYxIDAuMDU4NiAwLjI4NDE3IDAuMDk2NyAwLjQzMDY3IDAuMTE0MjYgMC4xNDY0NyAwLjAxNzYgMC4yOTU4OCAwLjAyNjQgMC40NDgyNCAwLjAyNjQgMC4xMjg4OSAwLjAwMDAyIDAuMjYwNzMtMC4wMDMgMC4zOTU1MS0wLjAwOSAwLjEzNDc1LTAuMDA2IDAuMjY2NTgtMC4wMDkgMC4zOTU1LTAuMDA5IDAuMTc1NzcgMC4wMDAwMiAwLjMzOTgzIDAuMDE3NiAwLjQ5MjE5IDAuMDUyNyAwLjE1MjMzIDAuMDM1MiAwLjI4NzEgMC4xMTEzNCAwLjQwNDMgMC4yMjg1MSAwLjExNzE3IDAuMTE3MjEgMC4yMDc5OSAwLjI4MTI3IDAuMjcyNDYgMC40OTIxOSAwLjA2NDQgMC4yMTA5NSAwLjA5NjcgMC40OTgwNiAwLjA5NjcgMC44NjEzMy0wLjAwMDAyIDAuNTYyNTEtMC4wNTg2IDEuMTg2NTQtMC4xNzU3OCAxLjg3MjA3LTAuMTE3MjEgMC42ODU1Ni0wLjI0NjExIDEuMzg4NjgtMC4zODY3MiAyLjEwOTM4LTAuMTQwNjQgMC43MjA3MS0wLjMxMDU2IDEuNTM4MDktMC41MDk3NyAyLjQ1MjE0LTAuMTk5MjMgMC45MTQwNy0wLjI5ODg0IDEuNjkzMzctMC4yOTg4MyAyLjMzNzg5LTAuMDAwMDEgMC41NTA3OSAwLjA0OTggMC45ODE0NSAwLjE0OTQyIDEuMjkyIDAuMDk5NiAwLjMxMDU1IDAuMzEzNDYgMC40NjU4MiAwLjY0MTYgMC40NjU4MiAwLjI0NjA4IDAgMC41MDM4OS0wLjA2MTUgMC43NzM0NC0wLjE4NDU3IDAuMjY5NTEtMC4xMjMwNSAwLjU0MTk3LTAuMjg0MTggMC44MTczOC0wLjQ4MzQgMC4yNzUzNy0wLjE5OTIyIDAuNTUwNzctMC40MjQ4IDAuODI2MTctMC42NzY3NiAwLjI3NTM4LTAuMjUxOTUgMC41MzYxMi0wLjUxMjY5IDAuNzgyMjMtMC43ODIyMyAwLjU3NDItMC42MzI4IDEuMTU0MjgtMS4zNDc2NCAxLjc0MDIzLTIuMTQ0NTN6bS0xNS4wMTE3Mi0xLjM4ODY4YzAgMC4yOTI5OCAwLjAyMDUgMC41NzcxNiAwLjA2MTUgMC44NTI1NCAwLjA0MSAwLjI3NTQgMC4xMTQyNSAwLjUyMTQ5IDAuMjE5NzIgMC43MzgyOCAwLjEwNTQ3IDAuMjE2ODEgMC4yNDYwOSAwLjM5MjU5IDAuNDIxODggMC41MjczNSAwLjE3NTc3IDAuMTM0NzcgMC4zOTg0MyAwLjIwMjE1IDAuNjY3OTcgMC4yMDIxNSAwLjQzMzU5IDAgMC44MjYxNi0wLjE4NzUgMS4xNzc3My0wLjU2MjUgMC4zNTE1Ni0wLjM3NSAwLjY1NjI0LTAuODI2MTcgMC45MTQwNy0xLjM1MzUyIDAuMjU3OC0wLjUyNzM0IDAuNDY1ODEtMS4wNzIyNiAwLjYyNDAyLTEuNjM0NzYgMC4xNTgxOS0wLjU2MjUgMC4yNjA3My0xLjAzMTI1IDAuMzA3NjItMS40MDYyNWwxLjMxODM2LTUuNzQ4MDVjLTAuNDgwNDggMC4wMDAwMS0wLjk0OTIzIDAuMTIwMTMtMS40MDYyNSAwLjM2MDM1LTAuNDU3MDUgMC4yNDAyNS0wLjg4NzcxIDAuNTY1NDQtMS4yOTIgMC45NzU1OS0wLjQwNDMgMC40MTAxNy0wLjc3NjM3IDAuODc4OTEtMS4xMTYyMSAxLjQwNjI1LTAuMzM5ODUgMC41MjczNS0wLjY3MzgzIDEuMTc3NzQtMS4wMDE5NSAxLjk1MTE3LTAuMzI4MTMgMC43NzM0NC0wLjU1OTU4IDEuNDUzMTMtMC42OTQzNCAyLjAzOTA2LTAuMTM0NzcgMC41ODU5NS0wLjIwMjE1IDEuMTM2NzMtMC4yMDIxNSAxLjY1MjM0ek0xNTkuMzEyNSA0NTMuNDUxNDhjLTEuMTI1MDIgMC42NTYyNS0yLjEyNjk3IDEuMjMwNDctMy4wMDU4NiAxLjcyMjY1LTAuODc4OTIgMC40OTIxOS0xLjY2MTE0IDAuOTAyMzUtMi4zNDY2OCAxLjIzMDQ3LTAuNjg1NTYgMC4zMjgxMy0xLjI5NDkzIDAuNTc0MjItMS44MjgxMiAwLjczODI4LTAuNTMzMjIgMC4xNjQwNy0xLjAxNjYxIDAuMjQ2MS0xLjQ1MDIgMC4yNDYxLTAuODkwNjMgMC0xLjY3NTc5LTAuMjY5NTMtMi4zNTU0Ny0wLjgwODYtMC42Nzk2OS0wLjUzOTA2LTEuMjQ4MDUtMS4zMTU0Mi0xLjcwNTA4LTIuMzI5MS0wLjQ1NzAzLTEuMDEzNjctMC43OTk4LTIuMjQxMi0xLjAyODMyLTMuNjgyNjItMC4yMjg1Mi0xLjQ0MTM5LTAuMzQyNzctMy4wNjQ0NC0wLjM0Mjc3LTQuODY5MTQgMC0wLjcwMzExIDAuMDI5My0xLjQ1MzExIDAuMDg3OS0yLjI1IDAuMDU4Ni0wLjc5Njg2IDAuMTQwNjItMS41ODc4NyAwLjI0NjA5LTIuMzczMDQtMC4zNTE1Ni0wLjA0NjktMC42MjQwMi0wLjA5MDgtMC44MTczOC0wLjEzMTg0LTAuMTkzMzYtMC4wNDEtMC4zOTg0NC0wLjA4Mi0wLjYxNTIzLTAuMTIzMDUtMC4yMTY4LTAuMDQxLTAuNDkyMTktMC4wODQ5LTAuODI2MTctMC4xMzE4My0wLjMzMzk5LTAuMDQ2OS0wLjgxNzM5LTAuMTA1NDUtMS40NTAyLTAuMTc1NzgtMC4yODEyNS0wLjAzNTEtMC40OTgwNS0wLjEwMjUzLTAuNjUwMzktMC4yMDIxNS0wLjE1MjM0LTAuMDk5Ni0wLjI2MzY3LTAuMjE2NzgtMC4zMzM5OS0wLjM1MTU2LTAuMDcwMy0wLjEzNDc1LTAuMTExMzItMC4yNzUzOC0wLjEyMzA0LTAuNDIxODgtMC4wMTE3LTAuMTQ2NDctMC4wMTc2LTAuMjcyNDQtMC4wMTc2LTAuMzc3OTMgMC0wLjIzNDM2IDAuMTE0MjYtMC40MTMwNyAwLjM0Mjc3LTAuNTM2MTMgMC4yMjg1Mi0wLjEyMzAzIDAuNTIxNDktMC4yMTM4NSAwLjg3ODkxLTAuMjcyNDYgMC4zNTc0Mi0wLjA1ODYgMC43NS0wLjA5MDggMS4xNzc3My0wLjA5NjcgMC40Mjc3NC0wLjAwNiAwLjgzNzg5LTAuMDAzIDEuMjMwNDcgMC4wMDkgMC4zOTI1OCAwLjAxMTcgMC43NDQxNCAwLjAyMDUgMS4wNTQ2OSAwLjAyNjQgMC4zMTA1NCAwLjAwNiAwLjUyNDQxLTAuMDAzIDAuNjQxNi0wLjAyNjQgMC4zMTY0LTEuNTgyMDEgMC43MTc3Ny0zLjExNDI0IDEuMjA0MS00LjU5NjY4IDAuNDg2MzMtMS40ODI0IDEuMDMxMjUtMi44MDA3NiAxLjYzNDc3LTMuOTU1MDggMC42MDM1MS0xLjE1NDI3IDEuMjYyNjktMi4wODAwNSAxLjk3NzU0LTIuNzc3MzQgMC43MTQ4My0wLjY5NzI0IDEuNDY0ODMtMS4wNDU4NyAyLjI1LTEuMDQ1OSAwLjUwMzg5IDAuMDAwMDMgMC45NjM4NSAwLjE0MDY1IDEuMzc5ODggMC40MjE4NyAwLjQxNiAwLjI4MTI4IDAuNzcwNSAwLjY1NjI4IDEuMDYzNDggMS4xMjUgMC4yOTI5NSAwLjQ2ODc4IDAuNTE4NTQgMS4wMTY2MyAwLjY3Njc2IDEuNjQzNTYgMC4xNTgxOCAwLjYyNjk4IDAuMjM3MjkgMS4yODAzIDAuMjM3MyAxLjk1OTk2LTAuMDAwMDEgMC41NzQyNC0wLjA4MjEgMS4xODA2OS0wLjI0NjA5IDEuODE5MzMtMC4xNjQwOCAwLjYzODctMC4zODY3NCAxLjI4OTA5LTAuNjY3OTcgMS45NTExOC0wLjI4MTI3IDAuNjYyMTMtMC42MTIzMiAxLjMzMzAyLTAuOTkzMTcgMi4wMTI2OS0wLjM4MDg3IDAuNjc5NzEtMC43ODIyMyAxLjM1MzU0LTEuMjA0MSAyLjAyMTQ5IDAuMTA1NDYgMC4wMTE3IDAuMjgxMjQgMC4wMTc2IDAuNTI3MzUgMC4wMTc2IDAuMjQ2MDggMC4wMDAwMiAwLjUzMDI2IDAuMDA5IDAuODUyNTMgMC4wMjY0IDAuMzIyMjYgMC4wMTc2IDAuNjcwODkgMC4wMzUyIDEuMDQ1OSAwLjA1MjcgMC4zNzQ5OSAwLjAxNzYgMC43MzgyNyAwLjAzMjMgMS4wODk4NSAwLjA0NCAwLjM1MTU0IDAuMDExNyAwLjY3OTY3IDAuMDIzNSAwLjk4NDM3IDAuMDM1MiAwLjMwNDY3IDAuMDExNyAwLjU1NjYzIDAuMDIzNSAwLjc1NTg2IDAuMDM1MSAwLjIzNDM2IDAuMDExNyAwLjQwNDI4IDAuMTI4OTMgMC41MDk3NyAwLjM1MTU3IDAuMTA1NDUgMC4yMjI2NyAwLjE1ODE4IDAuNTAzOTIgMC4xNTgyIDAuODQzNzUtMC4wMDAwMiAwLjQxMDE3LTAuMTE3MiAwLjc0NDE1LTAuMzUxNTYgMS4wMDE5NS0wLjIzNDM5IDAuMjU3ODMtMC40OTIyMSAwLjM4NjczLTAuNzczNDQgMC4zODY3MmwtNi43NSAwYy0wLjMzOTg1IDAuNDY4NzYtMC42ODI2MyAwLjkyODcyLTEuMDI4MzIgMS4zNzk4OC0wLjM0NTcxIDAuNDUxMTktMC42NjUwNSAwLjg1ODQxLTAuOTU4MDEgMS4yMjE2OC0wLjA0NjkgMC43MjY1Ny0wLjA4NzkgMS40NDE0Mi0wLjEyMzA1IDIuMTQ0NTMtMC4wMTE3IDAuMzA0Ny0wLjAyMzQgMC42MTgxOC0wLjAzNTIgMC45NDA0My0wLjAxMTcgMC4zMjIyOC0wLjAyMzQgMC42NDE2MS0wLjAzNTIgMC45NTgwMS0wLjAxMTcgMC4zMTY0MS0wLjAyMDUgMC42MjExLTAuMDI2NCAwLjkxNDA2LTAuMDA2IDAuMjkyOTgtMC4wMDkgMC41NjI1MS0wLjAwOSAwLjgwODU5LTAuMDAwMDEgMC42MzI4MiAwLjA1ODYgMS4xNjYwMyAwLjE3NTc4IDEuNTk5NjEgMC4xMTcxOCAwLjQzMzYgMC4yODcxIDAuNzg4MDkgMC41MDk3NiAxLjA2MzQ4IDAuMjIyNjUgMC4yNzU0IDAuNDk1MTEgMC40NzQ2MSAwLjgxNzM5IDAuNTk3NjYgMC4zMjIyNSAwLjEyMzA1IDAuNjgyNiAwLjE4NDU3IDEuMDgxMDUgMC4xODQ1NyAwLjI0NjA4IDAgMC41NTM3LTAuMDU4NiAwLjkyMjg1LTAuMTc1NzggMC4zNjkxMy0wLjExNzE5IDAuNzczNDMtMC4yNzgzMiAxLjIxMjg5LTAuNDgzNCAwLjQzOTQ0LTAuMjA1MDggMC45MDIzMy0wLjQ0ODI0IDEuMzg4NjctMC43Mjk0OSAwLjQ4NjMyLTAuMjgxMjUgMC45NjY3OS0wLjU4ODg3IDEuNDQxNDEtMC45MjI4NiAwLjQ3NDYtMC4zMzM5NyAwLjkyODctMC42ODU1NCAxLjM2MjMxLTEuMDU0NjggMC40MzM1Ny0wLjM2OTE0IDAuODI2MTUtMC43NDcwNyAxLjE3NzczLTEuMTMzNzl6bS01LjYyNS0yMi4yNTM5MWMtMC4wMDAwMS0wLjEyODg4LTAuMDA5LTAuMjcyNDMtMC4wMjY0LTAuNDMwNjYtMC4wMTc2LTAuMTU4MTgtMC4wNTU3LTAuMzA3NTktMC4xMTQyNi0wLjQ0ODI1LTAuMDU4Ni0wLjE0MDU5LTAuMTQ5NDItMC4yNTc3OC0wLjI3MjQ2LTAuMzUxNTYtMC4xMjMwNS0wLjA5MzctMC4yOTU5LTAuMTQwNi0wLjUxODU1LTAuMTQwNjItMC4zMDQ3IDAuMDAwMDItMC42MTIzMiAwLjI2OTU2LTAuOTIyODUgMC44MDg1OS0wLjMxMDU2IDAuNTM5MDktMC42MDkzOSAxLjIyNzU3LTAuODk2NDkgMi4wNjU0My0wLjI4NzExIDAuODM3OTEtMC41NTY2NSAxLjc2MDc3LTAuODA4NTkgMi43Njg1Ni0wLjI1MTk2IDEuMDA3ODMtMC40ODM0MSAxLjk4NjM0LTAuNjk0MzQgMi45MzU1NCAwLjE3NTc4IDAuMDIzNSAwLjM1MTU2IDAuMDQ0IDAuNTI3MzUgMC4wNjE1IDAuMTc1NzcgMC4wMTc2IDAuMzU3NDEgMC4wMzgxIDAuNTQ0OTIgMC4wNjE1IDAuMzUxNTUtMC41OTc2NCAwLjcxNzc2LTEuMjQ1MSAxLjA5ODYzLTEuOTQyMzggMC4zODA4NS0wLjY5NzI1IDAuNzIzNjMtMS4zODI3OSAxLjAyODMyLTIuMDU2NjQgMC4zMDQ2OC0wLjY3MzgxIDAuNTU2NjMtMS4zMDM2OSAwLjc1NTg2LTEuODg5NjUgMC4xOTkyMS0wLjU4NTkyIDAuMjk4ODItMS4wNjYzOCAwLjI5ODgzLTEuNDQxNDF6TTE2NS41NTI3MyA0NDguNzA1MzhjLTAuMzM5ODUgMS4yNzczNS0wLjc0MTIyIDIuNDQ2My0xLjIwNDEgMy41MDY4NC0wLjQ2MjkgMS4wNjA1NS0wLjk1ODAxIDEuOTc0NjEtMS40ODUzNSAyLjc0MjE5LTAuNTI3MzUgMC43Njc1OC0xLjA3MjI3IDEuMzY1MjMtMS42MzQ3NiAxLjc5Mjk3LTAuNTYyNTEgMC40Mjc3My0xLjExMzI5IDAuNjQxNi0xLjY1MjM1IDAuNjQxNi0wLjkxNDA2IDAtMS41ODQ5Ni0wLjY3MzgzLTIuMDEyNjktMi4wMjE0OS0wLjQyNzc0LTEuMzQ3NjUtMC42NDE2MS0zLjMzOTg0LTAuNjQxNjEtNS45NzY1NiAwLTAuMzc0OTkgMC0wLjcwNjA1IDAtMC45OTMxNiAwLTAuMjg3MSAwLjAwNi0wLjU3NDIxIDAuMDE3Ni0wLjg2MTMzIDAuMDExNy0wLjI4NzEgMC4wNTg2LTAuNzE0ODQgMC4xNDA2My0xLjI4MzIxIDAuMDgyLTAuNTY4MzQgMC4xNDA2Mi0xLjA4NjkgMC4xNzU3OC0xLjU1NTY2IDAuMDExNy0wLjExNzE3IDAuMDM4MS0wLjMxOTMyIDAuMDc5MS0wLjYwNjQ0IDAuMDQxLTAuMjg3MSAwLjEwNTQ3LTAuNjEyMyAwLjE5MzM2LTAuOTc1NTkgMC4wODc5LTAuMzYzMjcgMC4yMDUwOC0wLjc0MTIgMC4zNTE1Ni0xLjEzMzc5IDAuMTQ2NDgtMC4zOTI1NiAwLjMzMzk4LTAuNzQ5OTggMC41NjI1LTEuMDcyMjcgMC4yMjg1Mi0wLjMyMjI0IDAuNDk4MDUtMC41ODg4NSAwLjgwODYtMC43OTk4IDAuMzEwNTQtMC4yMTA5MiAwLjY4MjYxLTAuMzE2MzkgMS4xMTYyMS0wLjMxNjQxbDAuNDIxODcgMC4wMzUyYzAuNTE1NjIgMC4wMDAwMiAwLjkxMTEzIDAuMTAyNTYgMS4xODY1MyAwLjMwNzYyIDAuMjc1MzggMC4yMDUwOSAwLjQxMzA4IDAuNDc3NTUgMC40MTMwOCAwLjgxNzM4bC0xLjQwNjI1IDEwLjIxMjg5IDAuMjI4NTItMC4wMTc2YzAuNTE1NjItMS4xOTUzIDAuOTkzMTYtMi40MDUyNiAxLjQzMjYxLTMuNjI5ODggMC40Mzk0NS0xLjIyNDYgMC44MjAzMS0yLjIyOTQ4IDEuMTQyNTgtMy4wMTQ2NSAwLjMyMjI2LTAuNzg1MTQgMC42MzI4MS0xLjQ3MDY5IDAuOTMxNjQtMi4wNTY2NCAwLjI5ODgyLTAuNTg1OTIgMC42MjQwMi0xLjA3MjI1IDAuOTc1NTktMS40NTg5OCAwLjM1MTU1LTAuMzg2NzEgMC43NDcwNi0wLjY3Njc0IDEuMTg2NTItMC44NzAxMiAwLjQzOTQ0LTAuMTkzMzQgMC45NjM4Ni0wLjI5MDAyIDEuNTczMjQtMC4yOTAwNCAwLjUxNTYyIDAuMDAwMDIgMC45MTExMiAwLjEwMjU2IDEuMTg2NTMgMC4zMDc2MiAwLjI3NTM3IDAuMjA1MDkgMC40MTMwNyAwLjQ3NzU1IDAuNDEzMDggMC44MTczOGwtMS42MzQ3NiAxMC4yMTI4OSAwLjI2MzY3LTAuMDE3NiA0LjM3Njk1LTEwLjA4OTg0YzAuMTQwNjEtMC4zMjgxMSAwLjQ0NTMtMC42MTgxNSAwLjkxNDA3LTAuODcwMTIgMC40Njg3My0wLjI1MTkzIDEuMDI1MzctMC4zNzc5MSAxLjY2OTkyLTAuMzc3OTMgMC42Njc5NSAwLjAwMDAyIDEuMjA3MDEgMC4xMzc3MSAxLjYxNzE5IDAuNDEzMDkgMC40MTAxMyAwLjI3NTQxIDAuNjE1MjEgMC42Mjk5IDAuNjE1MjMgMS4wNjM0Ny0wLjAwMDAyIDAuMzI4MTUtMC4wNTU3IDAuNjc5NzEtMC4xNjY5OSAxLjA1NDY5LTAuMTExMzUgMC4zNzUwMi0wLjIzMTQ3IDAuNzIzNjUtMC4zNjAzNSAxLjA0NTktMC4xMjg5MyAwLjMyMjI4LTAuMjUxOTggMC41OTc2Ny0wLjM2OTE0IDAuODI2MTctMC4xMTcyMSAwLjIyODUzLTAuMTgxNjcgMC4zNTQ1MS0wLjE5MzM2IDAuMzc3OTMtMC4yNTc4NCAwLjQ2ODc2LTAuNTI0NDQgMS4wMTk1NS0wLjc5OTgxIDEuNjUyMzUtMC4yNzU0MSAwLjYzMjgyLTAuNDk1MTQgMS4xMDE1Ny0wLjY1OTE4IDEuNDA2MjUtMC4xOTkyNCAwLjM1MTU3LTAuMzc1MDIgMC42Nzk2OS0wLjUyNzM0IDAuOTg0MzcgMC4xNjQwNCAwLjQ1NzA0IDAuMzc0OTggMC44NjcyIDAuNjMyODEgMS4yMzA0NyAwLjIyMjY0IDAuMzA0NjkgMC40OTgwMyAwLjU4NTk0IDAuODI2MTcgMC44NDM3NSAwLjMyODExIDAuMjU3ODIgMC43MjA2OCAwLjM4NjcyIDEuMTc3NzQgMC4zODY3MiAwLjM4NjY5IDAgMC44MzIwMS0wLjEwMjUzIDEuMzM1OTMtMC4zMDc2MiAwLjUwMzg5LTAuMjA1MDcgMC45Nzg1LTAuNDM2NTIgMS40MjM4My0wLjY5NDMzIDAuNTI3MzItMC4yOTI5NyAxLjA2NjM4LTAuNjI2OTUgMS42MTcxOS0xLjAwMTk2bDAgNC4xMzA4NmMtMC43NzM0NiAwLjQ5MjE5LTEuNTczMjcgMC44ODQ3Ny0yLjM5OTQxIDEuMTc3NzQtMC44MjYyIDAuMjkyOTctMS42MjAxNCAwLjQzOTQ1LTIuMzgxODQgMC40Mzk0NS0wLjU5NzY4IDAtMS4xNjMxMS0wLjA3MDMtMS42OTYyOS0wLjIxMDk0LTAuNTMzMjItMC4xNDA2Mi0xLjAwMTk3LTAuMzYzMjgtMS40MDYyNS0wLjY2Nzk3LTAuNDA0MzEtMC4zMDQ2OC0wLjcyNjU4LTAuNzAwMTktMC45NjY4LTEuMTg2NTItMC4yNDAyNS0wLjQ4NjMyLTAuMzY2MjItMS4wNzUxOS0wLjM3NzkzLTEuNzY2Ni0wLjM5ODQ1IDAuOTM3NS0wLjgxNDQ2IDEuNzk1OS0xLjI0ODA0IDIuNTc1MTktMC40MzM2MSAwLjc3OTMtMC44Nzg5MiAxLjQ1MDItMS4zMzU5NCAyLjAxMjctMC40NTcwNSAwLjU2MjUtMC45MTk5NCAxLjAwMTk1LTEuMzg4NjcgMS4zMTgzNi0wLjQ2ODc2IDAuMzE2NC0wLjkzNzUxIDAuNDc0NjEtMS40MDYyNSAwLjQ3NDYxLTAuMTY0MDggMC0wLjMwNzYzLTAuMTI1OTgtMC40MzA2Ny0wLjM3NzkzLTAuMTIzMDUtMC4yNTE5Ni0wLjIyNTU5LTAuNTkxOC0wLjMwNzYxLTEuMDE5NTMtMC4wODIxLTAuNDI3NzQtMC4xNDk0My0wLjkyNTc4LTAuMjAyMTUtMS40OTQxNC0wLjA1MjctMC41NjgzNi0wLjA5MzgtMS4xNjg5NS0wLjEyMzA1LTEuODAxNzYtMC4wMjkzLTAuNjMyODEtMC4wNDk4LTEuMjgzMi0wLjA2MTUtMS45NTExNy0wLjAxMTctMC42Njc5Ny0wLjAxNzYtMS4zMTgzNi0wLjAxNzYtMS45NTExOHpNMTg2LjU1ODU5IDQ1Ny4zODg5OGMtMS4xMDE1NyAwLTIuMDY1NDMtMC4xOTkyMi0yLjg5MTYtMC41OTc2Ni0wLjgyNjE3LTAuMzk4NDQtMS41MTQ2NS0wLjkzNDU3LTIuMDY1NDMtMS42MDg0LTAuNTUwNzgtMC42NzM4Mi0wLjk2Mzg3LTEuNDUzMTItMS4yMzkyNi0yLjMzNzg5LTAuMjc1MzktMC44ODQ3Ni0wLjQxMzA4LTEuODEzNDctMC40MTMwOC0yLjc4NjEzIDAtMC42NTYyNCAwLjEyMzA0LTEuNDM4NDcgMC4zNjkxNC0yLjM0NjY4IDAuMjQ2MDktMC45MDgxOSAwLjUwMDk3LTEuNzEzODYgMC43NjQ2NS0yLjQxNjk5IDAuMjYzNjctMC43MDMxMiAwLjU5NDcyLTEuMzc0MDEgMC45OTMxNi0yLjAxMjcgMC4zOTg0NC0wLjYzODY2IDAuODY0MjYtMS4yMDExNSAxLjM5NzQ2LTEuNjg3NSAwLjUzMzItMC40ODYzMSAxLjEzMDg2LTAuODczMDMgMS43OTI5Ny0xLjE2MDE1IDAuNjYyMS0wLjI4NzEgMS4zOTc0NS0wLjQzMDY1IDIuMjA2MDYtMC40MzA2NyAwLjY1NjI0IDAuMDAwMDIgMS4yNjU2MSAwLjEwNTQ5IDEuODI4MTIgMC4zMTY0MSAwLjU2MjQ5IDAuMjEwOTUgMS4wNTE3NSAwLjUwNjg1IDEuNDY3NzcgMC44ODc2OSAwLjQxNjAxIDAuMzgwODggMC43NDEyIDAuODQwODQgMC45NzU1OSAxLjM3OTg5IDAuMjM0MzYgMC41MzkwNyAwLjM1MTU1IDEuMTQyNTkgMC4zNTE1NiAxLjgxMDU0LTAuMDAwMDEgMC43MjY1OC0wLjIzNzMyIDEuNTQxMDMtMC43MTE5MSAyLjQ0MzM2LTAuNDc0NjIgMC45MDIzNi0xLjAyODMzIDEuNjc1NzktMS42NjExMyAyLjMyMDMxLTAuNjMyODMgMC42NDQ1NC0xLjM4NTc2IDEuMjE4NzYtMi4yNTg3OSAxLjcyMjY2LTAuODczMDYgMC41MDM5MS0xLjgxOTM1IDAuODk2NDktMi44Mzg4NyAxLjE3Nzc0IDAuMTc1NzcgMC4zMTY0MSAwLjM0ODYzIDAuNTgwMDggMC41MTg1NSAwLjc5MTAxIDAuMTY5OTIgMC4yMTA5NCAwLjM0Mjc3IDAuMzc1IDAuNTE4NTYgMC40OTIxOSAwLjE3NTc3IDAuMTE3MTkgMC4zNjAzNCAwLjE5OTIyIDAuNTUzNzEgMC4yNDYwOSAwLjE5MzM1IDAuMDQ2OSAwLjQwMTM2IDAuMDcwMyAwLjYyNDAyIDAuMDcwMyAwLjcwMzEyIDAuMDAwMDEgMS40NDQzMy0wLjE0MDYyIDIuMjIzNjQtMC40MjE4NyAwLjc3OTI4LTAuMjgxMjUgMS41MzgwNy0wLjYzMjgxIDIuMjc2MzYtMS4wNTQ2OSAwLjczODI3LTAuNDIxODcgMS40MzU1NC0wLjg4MTgzIDIuMDkxOC0xLjM3OTg4IDAuNjU2MjMtMC40OTgwNCAxLjIxMjg3LTAuOTY5NzIgMS42Njk5Mi0xLjQxNTA0bDIuMjY3NTggMi4zNTU0N2MtMS4wMzEyNyAxLjA2NjQxLTIuMTI2OTcgMi4wMDk3Ny0zLjI4NzExIDIuODMwMDgtMC41MDM5MiAwLjM1MTU2LTEuMDQ1OTEgMC42OTcyNi0xLjYyNTk4IDEuMDM3MTEtMC41ODAwOSAwLjMzOTg0LTEuMTg5NDYgMC42NDE2LTEuODI4MTIgMC45MDUyNy0wLjYzODY4IDAuMjYzNjctMS4zMDA3OSAwLjQ3NDYxLTEuOTg2MzMgMC42MzI4MS0wLjY4NTU1IDAuMTU4MjEtMS4zNzk4OSAwLjIzNzMxLTIuMDgzMDEgMC4yMzczMXptLTIuMzM3ODktOC4yNjE3MmMwLjQyMTg3IDAuMDAwMDEgMC44ODE4My0wLjEwODM5IDEuMzc5ODktMC4zMjUyIDAuNDk4MDQtMC4yMTY3OSAwLjk2MDkzLTAuNDk1MTEgMS4zODg2Ny0wLjgzNDk2IDAuNDI3NzItMC4zMzk4MyAwLjg0NjY3LTAuNzk2ODYgMS4yNTY4My0xLjM3MTA5IDAuNDEwMTUtMC41NzQyMSAwLjYxNTIzLTEuMDQ4ODIgMC42MTUyNC0xLjQyMzgzLTAuMDAwMDEtMC41MjczMy0wLjA5NjctMC45NjA5Mi0wLjI5MDA0LTEuMzAwNzgtMC4xOTMzNy0wLjMzOTgzLTAuNDMwNjctMC41MDk3NS0wLjcxMTkyLTAuNTA5NzctMC41MDM5MSAwLjAwMDAyLTAuOTM0NTcgMC4wOTM4LTEuMjkxOTkgMC4yODEyNS0wLjM1NzQzIDAuMTg3NTItMC42NTYyNSAwLjQzMDY4LTAuODk2NDggMC43Mjk1LTAuMjQwMjQgMC4yOTg4NC0wLjQzMzYgMC42Mzg2OC0wLjU4MDA4IDEuMDE5NTMtMC4xNDY0OSAwLjM4MDg3LTAuMzE2NDEgMC44NTI1NS0wLjUwOTc3IDEuNDE1MDQtMC4xOTMzNiAwLjU2MjUxLTAuMzAxNzYgMS4wMjU0LTAuMzI1MTkgMS4zODg2Ny0wLjAyMzQgMC4zNjMyOS0wLjAzNTIgMC42NzM4My0wLjAzNTIgMC45MzE2NHpNMTk5Ljg0NzY2IDQ1Ny4zODg5OGMtMS4xMDE1NyAwLTIuMDY1NDQtMC4xOTkyMi0yLjg5MTYxLTAuNTk3NjYtMC44MjYxNy0wLjM5ODQ0LTEuNTE0NjUtMC45MzQ1Ny0yLjA2NTQzLTEuNjA4NC0wLjU1MDc4LTAuNjczODItMC45NjM4Ni0xLjQ1MzEyLTEuMjM5MjUtMi4zMzc4OS0wLjI3NTQtMC44ODQ3Ni0wLjQxMzA5LTEuODEzNDctMC40MTMwOS0yLjc4NjEzIDAtMC42NTYyNCAwLjEyMzA1LTEuNDM4NDcgMC4zNjkxNC0yLjM0NjY4IDAuMjQ2MDktMC45MDgxOSAwLjUwMDk4LTEuNzEzODYgMC43NjQ2NS0yLjQxNjk5IDAuMjYzNjctMC43MDMxMiAwLjU5NDcyLTEuMzc0MDEgMC45OTMxNi0yLjAxMjcgMC4zOTg0NC0wLjYzODY2IDAuODY0MjYtMS4yMDExNSAxLjM5NzQ3LTEuNjg3NSAwLjUzMzE5LTAuNDg2MzEgMS4xMzA4NS0wLjg3MzAzIDEuNzkyOTYtMS4xNjAxNSAwLjY2MjExLTAuMjg3MSAxLjM5NzQ2LTAuNDMwNjUgMi4yMDYwNi0wLjQzMDY3IDAuNjU2MjQgMC4wMDAwMiAxLjI2NTYxIDAuMTA1NDkgMS44MjgxMiAwLjMxNjQxIDAuNTYyNDkgMC4yMTA5NSAxLjA1MTc1IDAuNTA2ODUgMS40Njc3OCAwLjg4NzY5IDAuNDE2IDAuMzgwODggMC43NDExOSAwLjg0MDg0IDAuOTc1NTggMS4zNzk4OSAwLjIzNDM2IDAuNTM5MDcgMC4zNTE1NSAxLjE0MjU5IDAuMzUxNTcgMS44MTA1NC0wLjAwMDAyIDAuNzI2NTgtMC4yMzczMiAxLjU0MTAzLTAuNzExOTIgMi40NDMzNi0wLjQ3NDYyIDAuOTAyMzYtMS4wMjgzMyAxLjY3NTc5LTEuNjYxMTMgMi4zMjAzMS0wLjYzMjgyIDAuNjQ0NTQtMS4zODU3NSAxLjIxODc2LTIuMjU4NzkgMS43MjI2Ni0wLjg3MzA2IDAuNTAzOTEtMS44MTkzNCAwLjg5NjQ5LTIuODM4ODcgMS4xNzc3NCAwLjE3NTc4IDAuMzE2NDEgMC4zNDg2MyAwLjU4MDA4IDAuNTE4NTYgMC43OTEwMSAwLjE2OTkxIDAuMjEwOTQgMC4zNDI3NiAwLjM3NSAwLjUxODU1IDAuNDkyMTkgMC4xNzU3OCAwLjExNzE5IDAuMzYwMzUgMC4xOTkyMiAwLjU1MzcxIDAuMjQ2MDkgMC4xOTMzNSAwLjA0NjkgMC40MDEzNiAwLjA3MDMgMC42MjQwMyAwLjA3MDMgMC43MDMxMSAwLjAwMDAxIDEuNDQ0MzItMC4xNDA2MiAyLjIyMzYzLTAuNDIxODcgMC43NzkyOC0wLjI4MTI1IDEuNTM4MDctMC42MzI4MSAyLjI3NjM3LTEuMDU0NjkgMC43MzgyNi0wLjQyMTg3IDEuNDM1NTMtMC44ODE4MyAyLjA5MTc5LTEuMzc5ODggMC42NTYyNC0wLjQ5ODA0IDEuMjEyODgtMC45Njk3MiAxLjY2OTkyLTEuNDE1MDRsMi4yNjc1OCAyLjM1NTQ3Yy0xLjAzMTI2IDEuMDY2NDEtMi4xMjY5NyAyLjAwOTc3LTMuMjg3MTEgMi44MzAwOC0wLjUwMzkyIDAuMzUxNTYtMS4wNDU5MSAwLjY5NzI2LTEuNjI1OTcgMS4wMzcxMS0wLjU4MDA5IDAuMzM5ODQtMS4xODk0NyAwLjY0MTYtMS44MjgxMyAwLjkwNTI3LTAuNjM4NjggMC4yNjM2Ny0xLjMwMDc5IDAuNDc0NjEtMS45ODYzMyAwLjYzMjgxLTAuNjg1NTUgMC4xNTgyMS0xLjM3OTg5IDAuMjM3MzEtMi4wODMgMC4yMzczMXptLTIuMzM3ODktOC4yNjE3MmMwLjQyMTg2IDAuMDAwMDEgMC44ODE4Mi0wLjEwODM5IDEuMzc5ODgtMC4zMjUyIDAuNDk4MDQtMC4yMTY3OSAwLjk2MDkzLTAuNDk1MTEgMS4zODg2Ny0wLjgzNDk2IDAuNDI3NzMtMC4zMzk4MyAwLjg0NjY3LTAuNzk2ODYgMS4yNTY4NC0xLjM3MTA5IDAuNDEwMTQtMC41NzQyMSAwLjYxNTIyLTEuMDQ4ODIgMC42MTUyMy0xLjQyMzgzLTAuMDAwMDEtMC41MjczMy0wLjA5NjctMC45NjA5Mi0wLjI5MDA0LTEuMzAwNzgtMC4xOTMzNy0wLjMzOTgzLTAuNDMwNjctMC41MDk3NS0wLjcxMTkxLTAuNTA5NzctMC41MDM5MiAwLjAwMDAyLTAuOTM0NTggMC4wOTM4LTEuMjkxOTkgMC4yODEyNS0wLjM1NzQzIDAuMTg3NTItMC42NTYyNiAwLjQzMDY4LTAuODk2NDkgMC43Mjk1LTAuMjQwMjQgMC4yOTg4NC0wLjQzMzYgMC42Mzg2OC0wLjU4MDA4IDEuMDE5NTMtMC4xNDY0OSAwLjM4MDg3LTAuMzE2NDEgMC44NTI1NS0wLjUwOTc2IDEuNDE1MDQtMC4xOTMzNyAwLjU2MjUxLTAuMzAxNzcgMS4wMjU0LTAuMzI1MiAxLjM4ODY3LTAuMDIzNCAwLjM2MzI5LTAuMDM1MiAwLjY3MzgzLTAuMDM1MSAwLjkzMTY0eiIgY2xhc3M9InMwIi8+PC9nPjwvc3ZnPg==');
	width: 127.97px;
	height: 39.13px;
	display: inline-block;
	margin: 6px 0 0 0;
}

.stuffbox
{
	box-sizing: border-box;
	padding: 25px;
	width: 78%;
	float: left;
	margin: 0 1% 0 0;
}

.postbox
{
	box-sizing: border-box;
	padding: 25px;
	width: 20%;
	float: left;
	margin: 0 1% 1% 0;
}

.option-info
{
	background: url("http://www.chatwee.com/public/images/infoIcon.png") no-repeat left top;					
	font-size: 13px;
	color: #555;
	margin-right: 20px;
	padding-left: 20px;
	margin-top: 5px;
	line-height: 14px;
	opacity: 0.7;
	
	-webkit-transition: opacity 0.5s;  
	-moz-transition: opacity 0.5s;  
	-o-transition: opacity 0.5s;  
	-ms-transition: opacity 0.5s;  
	transition: opacity 0.5s;					
}	

	.option-info:hover
	{
		opacity: 1;
		
		-webkit-transition: opacity 0.5s;  
		-moz-transition: opacity 0.5s;  
		-o-transition: opacity 0.5s;  
		-ms-transition: opacity 0.5s;  
		transition: opacity 0.5s;							
	}
	
.stuffbox article
{
	padding: 5px 0 35px 0;
}

	.chatwee-snippet
	{
		width: 420px;
		height: 80px;
		resize: none;
		font-size: 13px;
	}
	
article label
{
	width: 220px;
	font-size: 14px;
}

	article label.right
	{
		width: 180px;
		display: inline-block;
		text-align: right;
		margin: 0 9px 0 0;
	}	

article div
{
	padding: 8px 0;
	display: block;
}

	article div.right
	{
		padding-left: 189px;
	}

	#ajaxLoader {
		display:none;
	}
	
		#ajaxLoader img
		{
			vertical-align: -9px;
		}
	
	.dataTableSearch{
		display:none;
	}
	
	.dataTable, .dataTableSearch
	{
		font-size: 13px;
		border-collapse: collapse;
		margin: 30px 0 0 0;
	}
	
	.dataTable th, .dataTableSearch th {
		
		border: 1px solid #ccc;
		width:180px;
		padding: 5px 0;
	}
	
	.dataTable td, .dataTableSearch td
	{
		padding: 5px 0;
		text-align: center;
	}	
		
	td a
	{
		cursor:pointer;
	}
	
	</style>

</head>

<body>
	
	<div class="main-wrapper">
	
		<a href="http://www.chatwee.com" class="chatwee-logo"></a>
		<h1>WordPress Chat by Chatwee: General Settings</h1>
		
		<div class="stuffbox">	
			
			<form method="post" action="<?php echo $PHP_SELF;?>">				
			
				<article>
				
					<h2>Embed the installation code</h2>
					
					<div class="right">
						<textarea id="chatweesnippet" class="chatwee-snippet" onclick="this.select()" name="chatweesnippet" ><?php  echo get_option('chatwee') ?></textarea>	
						<p class="option-info">Paste your Chatwee installation code beneath. Get the free code by <a href="https://www.chatwee.com/social-chat-software/authorization/login" target="_blank">logging in to your Chatwee account</a>. If you don't have your own Chatwee account please <a target="_blank" href="https://www.chatwee.com/social-chat-software/register">Sign Up here</a></p>
					</div>
					
				</article>
				

				<article>
				
					<?php 
					
					
					$checked1 = get_option( 'chatwee-settings-group[is_home]');
					$checked2 = get_option( 'chatwee-settings-group[is_search]');
					$checked3 = get_option( 'chatwee-settings-group[is_archive]');
					$checked4 = get_option( 'chatwee-settings-group[is_single]');
					$checked5 = get_option( 'chatwee-settings-group[is_page]');
					$checked6 = get_option( 'chatwee-settings-group[ssostatus]');
					$checked7 = get_option( 'chatwee-settings-group[is_for_users]');
					$checked8 = get_option( 'chatwee-settings-group[loginallsubdomains]');
					
					?>				
				
					<h2>Display properties</h2>
					
					<p class="option-info">This section allows you to decide on what particular components of your WordPress site the chat will appear.</p>
					
					<div class="right">
						<input type="checkbox" id="is_for_users" name="chatwee-settings-group[is_for_users]" <?php checked( $checked7, "on", "checked"); ?>/> 
						<label for="is_for_users">Display chat only for logged-in users</label> 
					</div>
					<div class="right">
						<input type="checkbox" id="is_home" name="chatwee-settings-group[is_home]" <?php checked( $checked1, "on", "checked"); ?>/> 
						<label for="is_home">Display chat on a main page</label> 
					</div>
					<div class="right">
						<input type="checkbox" id="is_search" name="chatwee-settings-group[is_search]" <?php checked( $checked2, "on", "checked"); ?>/> 
						<label for="is_search">Display chat on a search page</label> 
					</div>  
					<div class="right">
						<input type="checkbox" id="is_archive" name="chatwee-settings-group[is_archive]" <?php checked( $checked3, "on", "checked"); ?>/> 
						<label for="is_archive">Display chat on an archive page</label>
					</div>
					<div class="right">
						<input type="checkbox" id="is_single" name="chatwee-settings-group[is_single]" <?php checked( $checked4,"on", "checked"); ?>/> 
						<label for="is_single">Display chat on a post page</label> 
					</div>
					<div class="right">
						<input type="checkbox" id="is_page" name="chatwee-settings-group[is_page]" <?php checked( $checked5,"on", "checked"); ?>/> 
						<label for="is_page">Display chat on a single page</label>
					</div>
					
				</article>
				
								
				<article>
					
					<h2>Single Sign-on</h2>
					
					<p class="option-info">Single Sign-on (SSO) permits user to sign into a site and fully use Chatwee chat platform without again authenticating with Chatwee. To fill in the form below with proper values please visit <a href="https://www.chatwee.com/client/customize#integration" target="_blank">Integration</a> section in Chatwee Control Panel.</p>
					
					<input type="hidden" name="chatwee-settings-group[ssoiserror]" value="<?php echo get_option('chatwee-settings-group[ssoiserror]'); ?>"/>

					<div class="right">					
						<input type="checkbox" id="ssostatus" name="chatwee-settings-group[ssostatus]"  <?php checked( $checked6,"on", "checked");?> />
						<label for="ssostatus">Enable SSO login</label> 
					</div>
					<div class="right">					
						<input type="checkbox" id="loginallsubdomains" name="chatwee-settings-group[loginallsubdomains]"  <?php checked( $checked8,"on", "checked");?> />
						<label for="loginallsubdomains">Login for all subdomains</label> 
					</div>
					<div>					
						<label class="right" for="clientid">ChatId</label> 
						<input type="text" id="clientid" name="chatwee-settings-group[clientid]" value="<?php echo get_option('chatwee-settings-group[clientid]'); ?>"/>
						<p class="option-info"></p>
					</div>
					<div>					
						<label class="right" for="keyapi">Chatwee API Key</label> 
						<input type="text" id="keyapi" name="chatwee-settings-group[keyapi]" size="50" value="<?php echo get_option('chatwee-settings-group[keyapi]'); ?>"/>
						<div class="right">
							<p class="option-info">API key is required to make Chatwee API calls. API Key is classified information, such as your account password - do not show it to third parties.</p>
						</div>
					</div>
					
					<p class="option-info">To learn more on how to use Single Sign-On and Chatwee API visit <a href="https://www.chatwee.com/single-sign-on" target="_blank">https://www.chatwee.com/single-sign-on</a> and <a href="https://www.chatwee.com/chatwee-api" target="_blank">https://www.chatwee.com/chatwee-api</a></p>
				</article>
				
				<article>
				
					<h2>Usernames Display Format</h2>
				
					<p class="option-info">Set display name format for users.</p>

					<select name='chatwee-settings-group[display_format]'>
						<? 
							echo chatwee_format_name(); 
						?>
					</select>
					
				</article>

				<div class="cwp-btn-wrapper">
					<input type="submit" class="button-primary" name="chatweesubmit" value="Save changes" /><span class="cwp-register-info">Don't have a Chatwee account? <a target="_blank" href="https://www.chatwee.com/social-chat-software/register">Sign up now for free.</a></span>
				</div>
			
			</form>
		
		</div>
		
		<div class="postbox">
		
			<h2>How to get Chatwee installation code?</h2>				
			<ol>
				<li><a href="https://www.chatwee.com/social-chat-software/authorization/login" target="_blank">Sign in</a> to your Chatwee control panel.</li>
				<li>Copy the code snippet that appears on the script page.</li>
				<li>Paste it above, and click 'Save Changes' button.</li>
				<li>Visit <a target="_blank" href="https://www.chatwee.com/integration">here</a> for more installation instructions.</li>
			</ol>
		
		</div>
		
		<div class="postbox">
			
			<a href="https://www.chatwee.com/client/order" target="_blank">			
				<img src="https://www.chatwee.com/public/images/chatwee-wp-upgrade-banner.png">			
			</a>
		</div>
	
	</div>

</body>

</html>



