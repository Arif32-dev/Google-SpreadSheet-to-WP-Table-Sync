export function rowsPerPage(isProActive) {
    const values = [
        { value: "1", label: "1" },
        { value: "5", label: "5" },
        { value: "10", label: "10" },
        { value: "15", label: "15" },
    ];
    if (isProActive) {
        values.push({ value: "25", label: "25" }, { value: "50", label: "50" }, { value: "100", label: "100" }, { value: "-1", label: "All" });
    }
    return values;
}
export function scrollHeights(isProActive, heights) {
    const values = [];
    if (isProActive) {
        if (heights) {
            for (const height in heights) {
                values.push({ value: height, label: heights[height]["val"] });
            }
        }
    }
    return values;
}
export function formatCellValues(isProActive) {
    const values = [];
    if (isProActive) {
        values.push({ value: "wrap", label: "Wrap Style" }, { value: "expand", label: "Expanded Style" });
    }
    return values;
}
export function redirectionValues(isProActive) {
    const values = [];
    if (isProActive) {
        values.push({ value: "_blank", label: "Blank Type" }, { value: "_self", label: "Self Type" });
    }
    return values;
}
export function tableStyles(isProActive, styles) {
    const values = [];
    if (isProActive) {
        if (styles) {
            for (const style in styles) {
                values.push({ value: style, label: styles[style]["label"] });
            }
        }
    }
    return values;
}
export function responsiveStyles(isProActive, styles) {
    const values = [];
    if (isProActive) {
        if (styles) {
            for (const style in styles) {
                values.push({ value: style, label: styles[style]["val"] });
            }
        }
    }
    return values;
}
