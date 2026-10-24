package com.meridian.billing;

import java.io.IOException;

import javax.servlet.RequestDispatcher;
import javax.servlet.ServletException;
import javax.servlet.annotation.WebServlet;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

/**
 * Handles the bill calculation form submission.
 *
 * On success, forwards to result.jsp with the itemized breakdown.
 * On invalid input, forwards back to index.jsp with an error message so
 * the user's already-entered values and a clear error are shown together.
 */
@WebServlet("/calculate")
public class BillCalculatorServlet extends HttpServlet {

    private static final long serialVersionUID = 1L;
    private static final int MAX_REASONABLE_UNITS = 100000;

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {

        String consumerName = safeTrim(request.getParameter("consumerName"));
        String unitsParam = safeTrim(request.getParameter("units"));

        String error = null;
        double units = 0;

        if (consumerName.isEmpty()) {
            error = "Please enter the consumer name.";
        } else if (unitsParam.isEmpty()) {
            error = "Please enter the number of units consumed.";
        } else {
            try {
                units = Double.parseDouble(unitsParam);
                if (units < 0) {
                    error = "Units consumed cannot be negative.";
                } else if (units > MAX_REASONABLE_UNITS) {
                    error = "That units value looks too large. Please check and try again.";
                }
            } catch (NumberFormatException e) {
                error = "Units consumed must be a valid number.";
            }
        }

        if (error != null) {
            request.setAttribute("errorMessage", error);
            request.setAttribute("consumerName", consumerName);
            request.setAttribute("unitsInput", unitsParam);
            RequestDispatcher dispatcher = request.getRequestDispatcher("/index.jsp");
            dispatcher.forward(request, response);
            return;
        }

        BillCalculator.BillResult result = BillCalculator.calculate(units);

        request.setAttribute("consumerName", consumerName);
        request.setAttribute("result", result);

        RequestDispatcher dispatcher = request.getRequestDispatcher("/result.jsp");
        dispatcher.forward(request, response);
    }

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        // Convenience: visiting /calculate directly just sends the user to the form.
        response.sendRedirect(request.getContextPath() + "/index.jsp");
    }

    private static String safeTrim(String value) {
        return value == null ? "" : value.trim();
    }
}
