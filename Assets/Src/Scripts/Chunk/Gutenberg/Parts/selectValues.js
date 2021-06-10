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
export function scrollHeights(isProActive) {
    const values = [];
    if (isProActive) {
        values.push(
            { key: "200", value: "200", text: "200px" },
            { key: "400", value: "400", text: "400px" },
            { key: "500", value: "500", text: "500px" },
            { key: "600", value: "600", text: "600px" },
            { key: "700", value: "700", text: "700px" },
            { key: "800", value: "800", text: "800px" },
            { key: "900", value: "900", text: "900px" },
            { key: "1000", value: "1000", text: "1000px" },
            { key: "default", value: "default", text: "Default Style" }
        );
    }
    return values;
}
export function formatCellValues(isProActive) {
    const values = [];
    if (isProActive) {
        values.push(
            { key: "clip", value: "clip", text: "Clip Style" },
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
