<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8" %>
<%@ taglib uri="http://java.sun.com/jsp/jstl/core" prefix="c" %>
<%@ taglib uri="http://java.sun.com/jsp/jstl/fmt" prefix="fmt" %>
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bill Summary — Ampere</title>
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
        <h2>Bill summary</h2>
        <p>Consumer: <strong>${consumerName}</strong> &middot; Units consumed: <strong><fmt:formatNumber value="${result.totalUnits}" maxFractionDigits="2"/> kWh</strong></p>
      </div>

      <div class="meter">
        <span class="meter__label">Total payable</span>
        <span class="meter__value">Rs. <fmt:formatNumber value="${result.totalAmount}" minFractionDigits="2" maxFractionDigits="2"/></span>
      </div>

      <div class="trace"></div>

      <section aria-label="Itemized breakdown">
        <h3 class="section-title">Slab-wise breakdown</h3>
        <table class="breakdown-table">
          <thead>
            <tr>
              <th>Slab</th>
              <th>Units</th>
              <th>Rate</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <c:forEach items="${result.slabs}" var="slab">
              <tr>
                <td>${slab.label}</td>
                <td><fmt:formatNumber value="${slab.units}" maxFractionDigits="2"/></td>
                <td>Rs. <fmt:formatNumber value="${slab.rate}" minFractionDigits="2" maxFractionDigits="2"/></td>
                <td>Rs. <fmt:formatNumber value="${slab.amount}" minFractionDigits="2" maxFractionDigits="2"/></td>
              </tr>
            </c:forEach>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="3">Total</td>
              <td>Rs. <fmt:formatNumber value="${result.totalAmount}" minFractionDigits="2" maxFractionDigits="2"/></td>
            </tr>
          </tfoot>
        </table>
      </section>

      <div class="result-actions">
        <a class="btn btn--ghost" href="${pageContext.request.contextPath}/index.jsp">&larr; Calculate another bill</a>
        <button class="btn btn--primary" onclick="window.print()">Print / Save PDF</button>
      </div>
    </main>

    <footer class="page-footer">
      <p>Built with Java Servlets + JSP &middot; Slab-based billing engine</p>
    </footer>
  </div>
</body>
</html>
