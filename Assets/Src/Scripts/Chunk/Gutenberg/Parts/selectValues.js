export function rowsPerPage(isProActive) {
    const values = [
        { key: "1", value: "1", text: "1" },
        { key: "5", value: "5", text: "5" },
        { key: "10", value: "10", text: "10" },
        { key: "15", value: "15", text: "15" },
    ];
    if (isProActive) {
        values.push(
            { key: "25", value: "25", text: "25" },
            { key: "50", value: "50", text: "50" },
            { key: "100", value: "100", text: "100" },
            { key: "-1", value: "-1", text: "All" }
        );
    }
    return values;
}
export function scrollHeights(isProActive, heights) {
    const values = [];
    if (isProActive) {
        if (heights) {
            for (const height in heights) {
                values.push({ key: height, value: height, text: heights[height]["val"] });
            }
        }
    }
    return values;
}
export function formatCellValues(isProActive) {
    const values = [];
    if (isProActive) {
        values.push(
            { key: "wrap", value: "wrap", text: "Wrap Style" },
            { key: "expand", value: "expand", text: "Expanded Style" }
        );
    }
    return values;
}
export function redirectionValues(isProActive) {
    const values = [];
    if (isProActive) {
        values.push(
            { key: "_blank", value: "_blank", text: "Blank Type" },
            { key: "_self", value: "_self", text: "Self Type" }
        );
    }
    return values;
}
export function tableStyles(isProActive, styles) {
    const values = [];
    if (isProActive) {
        if (styles) {
            for (const style in styles) {
                values.push({ key: style, value: style, text: styles[style]["label"] });
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
                values.push({ key: style, value: style, text: styles[style]["val"] });
            }
        }
    }
    return values;
}
