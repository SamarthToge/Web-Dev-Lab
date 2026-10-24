# Ampere — Electricity Bill Calculator (Java Servlet + JSP)

A responsive web app that calculates an electricity bill using a tiered
("slab") tariff, built with **Java Servlets**, **JSP**, and plain
HTML/CSS/JS — no frontend framework or database required.

## Tariff slabs

| Slab | Units | Rate |
|---|---|---|
| 1 | First 50 units | Rs. 3.50 / unit |
| 2 | Next 100 units (51–150) | Rs. 4.00 / unit |
| 3 | Next 100 units (151–250) | Rs. 5.20 / unit |
| 4 | Above 250 units | Rs. 6.50 / unit |

Each slab only charges for the units that actually fall in that band — e.g.
275 units bills as 50×3.50 + 100×4.00 + 100×5.20 + 25×6.50, not the top rate
on the whole 275.

## Project structure

```
electricity-bill-servlet/
├── pom.xml
├── src/main/java/com/meridian/billing/
│   ├── BillCalculator.java         pure slab-calculation logic (unit-testable)
│   └── BillCalculatorServlet.java  @WebServlet("/calculate") — validates input,
│                                    forwards to result.jsp or back to index.jsp
└── src/main/webapp/
    ├── index.jsp        the input form + tariff reference table
    ├── result.jsp        itemized bill breakdown + LED-style total readout
    ├── error.jsp         friendly fallback for uncaught exceptions
    ├── WEB-INF/web.xml   welcome file + error page config
    ├── css/style.css     responsive design system
    └── js/script.js      client-side validation (progressive enhancement only)
```

## How it works

1. `index.jsp` posts `consumerName` and `units` to `/calculate`.
2. `BillCalculatorServlet` validates the input server-side (required fields,
   non-negative number, sane upper bound). On failure it forwards back to
   `index.jsp` with an error message and the values the user already typed.
3. On success it calls `BillCalculator.calculate(units)`, which returns an
   itemized `BillResult` (per-slab units/rate/amount + total).
4. The result is forwarded to `result.jsp`, which renders the breakdown
   table and a glowing digital total using JSTL/EL — no scriptlets.

## Requirements

- JDK 11+
- Apache Maven 3.6+
- Apache Tomcat 9.x (Servlet 4.0 / javax.* namespace)

> Deploying to Tomcat 10+ or another Jakarta EE server? Swap the
> `javax.servlet` / `javax.servlet.jsp` groupIds in `pom.xml` for their
> `jakarta.servlet` equivalents and update the `javax.servlet.*` imports in
> `BillCalculatorServlet.java` to `jakarta.servlet.*`.

## Build

```bash
mvn clean package
```

This produces `target/electricity-bill.war`.

## Run locally

**Option A — drop into Tomcat:**

```bash
cp target/electricity-bill.war $CATALINA_HOME/webapps/
$CATALINA_HOME/bin/startup.sh   # or startup.bat on Windows
```

Visit `http://localhost:8080/electricity-bill/`.

**Option B — Maven Tomcat plugin (for local dev without a separate Tomcat install):**

Add the `tomcat7-maven-plugin` to `pom.xml` and run `mvn tomcat7:run`, or use
your IDE's built-in "Run on Server" support (Eclipse/IntelliJ both detect
this as a standard Maven WAR project).

## Notes

- All monetary values are rounded to 2 decimal places at each slab and in
  the total.
- The form includes a "Print / Save PDF" button on the result page for
  generating a simple receipt.
- Styling lives entirely in `css/style.css`; no build step or CSS
  preprocessor is required.

## License

MIT — see `LICENSE`.
