package com.meridian.billing;

import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

/**
 * Pure calculation logic for the tiered electricity tariff, kept separate
 * from the Servlet so it can be unit-tested independently of the web layer.
 *
 * Slabs:
 *   First 50 units        -> Rs. 3.50 / unit
 *   Next 100 units (51-150)  -> Rs. 4.00 / unit
 *   Next 100 units (151-250) -> Rs. 5.20 / unit
 *   Above 250 units        -> Rs. 6.50 / unit
 */
public final class BillCalculator {

    private static final double SLAB1_LIMIT = 50;
    private static final double SLAB2_LIMIT = 100;
    private static final double SLAB3_LIMIT = 100;

    private static final double SLAB1_RATE = 3.50;
    private static final double SLAB2_RATE = 4.00;
    private static final double SLAB3_RATE = 5.20;
    private static final double SLAB4_RATE = 6.50;

    private BillCalculator() {
        // static utility class
    }

    /** One line of the itemized bill breakdown, e.g. "51-150" at Rs.4.00/unit. */
    public static final class SlabDetail {
        public final String label;
        public final double units;
        public final double rate;
        public final double amount;

        SlabDetail(String label, double units, double rate) {
            this.label = label;
            this.units = units;
            this.rate = rate;
            this.amount = round(units * rate);
        }
    }

    /** Full calculation result: per-slab breakdown plus the total payable. */
    public static final class BillResult {
        public final double totalUnits;
        public final List<SlabDetail> slabs;
        public final double totalAmount;

        BillResult(double totalUnits, List<SlabDetail> slabs, double totalAmount) {
            this.totalUnits = totalUnits;
            this.slabs = Collections.unmodifiableList(slabs);
            this.totalAmount = totalAmount;
        }
    }

    /**
     * Calculates the itemized bill for the given number of consumed units.
     *
     * @throws IllegalArgumentException if units is negative
     */
    public static BillResult calculate(double units) {
        if (units < 0) {
            throw new IllegalArgumentException("Units consumed cannot be negative.");
        }

        List<SlabDetail> slabs = new ArrayList<>();
        double remaining = units;
        double total = 0;

        double slab1Units = Math.min(remaining, SLAB1_LIMIT);
        if (slab1Units > 0) {
            SlabDetail d = new SlabDetail("First 50 units (1-50)", slab1Units, SLAB1_RATE);
            slabs.add(d);
            total += d.amount;
        }
        remaining -= slab1Units;

        double slab2Units = Math.min(remaining, SLAB2_LIMIT);
        if (slab2Units > 0) {
            SlabDetail d = new SlabDetail("Next 100 units (51-150)", slab2Units, SLAB2_RATE);
            slabs.add(d);
            total += d.amount;
        }
        remaining -= slab2Units;

        double slab3Units = Math.min(remaining, SLAB3_LIMIT);
        if (slab3Units > 0) {
            SlabDetail d = new SlabDetail("Next 100 units (151-250)", slab3Units, SLAB3_RATE);
            slabs.add(d);
            total += d.amount;
        }
        remaining -= slab3Units;

        double slab4Units = Math.max(remaining, 0);
        if (slab4Units > 0) {
            SlabDetail d = new SlabDetail("Above 250 units (251+)", slab4Units, SLAB4_RATE);
            slabs.add(d);
            total += d.amount;
        }

        return new BillResult(units, slabs, round(total));
    }

    private static double round(double value) {
        return Math.round(value * 100.0) / 100.0;
    }
}
