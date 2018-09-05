<%@ Page Language="VB" ContentType="text/html" ResponseEncoding="UTF-8" %>
<!doctype html>

<%Option Explicit%>
<HTML>
<META http-Equiv="Refresh" CONTENT="180">
<TITLE>Prueba de acceso de base de datos</TITLE>
<body>

<%
Public con 
Public sapObj 
Public theFunc
Dim returnFunc, sapConnection, functionCtrl, retcd
Set sapObj = CreateObject("SAP.Functions") 
Set sapConnection = CreateObject("SAP.Logoncontrol.1") 
'Set sapConnection = sapConnection.NewConnection 
'Set sapConnection = sapObj.Connection 

sapConnection.SystemNumber = "20" 
sapConnection.ApplicationServer = "138.94.140.25"
sapConnection.client = "100"
sapConnection.user = "manager" 
sapConnection.Password = "2305" 
sapConnection.language = "EN" 

'************************************** 
'Log On to the SAP System 
'************************************** 
Set functionCtrl = server.CreateObject("SAP.Functions") 
retcd=sapConnection.Logon(0,true) 
If RetCd = False Then 
Response.write "SAP Logon Fallo" 
Response.End 
else 
Response.write "SAP Logon exitoso." 
end if 

'Hasta este punto me correo bien
'el problema ocurre cuando agrego el codigo siguiente:

' Logoff from SAP 
sapConnection.Connection.Logoff 


set sapConnection = Nothing 
Set functionCtrl = Nothing 
set theFunc = Nothing

%>
<body>
<HTML>
