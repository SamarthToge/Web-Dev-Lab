<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" isErrorPage="true" %>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Something went wrong — Ampere</title>
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>
  <div class="page">
    <header class="brand">
      <span class="brand__mark">&#9889;</span>
      <div>
        <h1>AMPERE</h1>
        <p>Electricity Bill Calculator</p>
      </div>
    </header>
    <main class="card">
      <div class="card__intro">
        <h2>Something tripped the circuit</h2>
        <p>An unexpected error occurred while processing your request. Please go back and try again.</p>
      </div>
      <div class="result-actions">
        <a class="btn btn--primary" href="${pageContext.request.contextPath}/index.jsp">&larr; Back to calculator</a>
      </div>
    </main>
  </div>
</body>
</html>
