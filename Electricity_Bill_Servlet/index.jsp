<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ampere — Electricity Bill Calculator</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=IBM+Plex+Mono:wght@400;500;600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
        <h2>Calculate your bill</h2>
        <p>Enter the units consumed this billing cycle to get an itemized, slab-wise breakdown.</p>
      </div>

      <c:if test="${not empty errorMessage}">
        <div class="alert" role="alert">${errorMessage}</div>
      </c:if>

      <form action="${pageContext.request.contextPath}/calculate" method="post" class="form" novalidate>
        <label class="field">
          <span>Consumer name</span>
          <input
            type="text"
            name="consumerName"
            placeholder="e.g. Anjali Verma"
            value="${consumerName}"
            required
            maxlength="80"
          >
        </label>

        <label class="field">
          <span>Units consumed (kWh)</span>
          <input
            type="number"
            name="units"
            step="0.01"
            min="0"
            placeholder="e.g. 275"
            value="${unitsInput}"
            required
            inputmode="decimal"
          >
        </label>

        <button type="submit" class="btn btn--primary">Calculate Bill</button>
      </form>

      <section class="tariff-table" aria-label="Tariff slabs">
        <h3>Tariff slabs</h3>
        <div class="trace"></div>
        <ul>
          <li><span>First 50 units</span><span class="tariff-table__rate">Rs. 3.50 / unit</span></li>
          <li><span>Next 100 units (51&ndash;150)</span><span class="tariff-table__rate">Rs. 4.00 / unit</span></li>
          <li><span>Next 100 units (151&ndash;250)</span><span class="tariff-table__rate">Rs. 5.20 / unit</span></li>
          <li><span>Above 250 units</span><span class="tariff-table__rate">Rs. 6.50 / unit</span></li>
        </ul>
      </section>
    </main>

    <footer class="page-footer">
      <p>Built with Java Servlets + JSP &middot; Slab-based billing engine</p>
    </footer>
  </div>

  <script src="${pageContext.request.contextPath}/js/script.js"></script>
</body>
</html>
