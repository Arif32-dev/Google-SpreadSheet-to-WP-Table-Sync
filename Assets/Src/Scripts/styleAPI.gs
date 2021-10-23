function doGet(req) {
    let sheetID = req.parameter.sheetID;
    let gID = req.parameter.gID;

    // if either sheetID or gID parameter is not found then return error response
    if (!sheetID || !gID) {
        return ContentService.createTextOutput(
            JSON.stringify({
                status: 404,
                response: "sheetID or gID required parameter is missing",
            })
        );
    }

    let requestObject = {
        sheetID,
        gID,
    };
    // get sheet cell style like background color, text color
    let sheetCellColor = getSheetCellStyles(requestObject);

    return ContentService.createTextOutput(JSON.stringify(sheetCellColor));
}
function getSheetCellStyles(requestObject) {
    let sheet = openSheetByID(requestObject);

    let lastColumn = sheet.getLastColumn();

    lastColumn = getLastColumChar(lastColumn);

    var range = sheet.getRange(`A1:${lastColumn}2`);
    var bgColors = range.getBackgrounds();

    return bgColors;
}

// Open the google sheet with sheet ID
function openSheetByID(requestObject) {
    var ss = SpreadsheetApp.openById(requestObject.sheetID);

    let allTabs = ss.getSheets();

    let sheet;

    for (i = 0; i < allTabs.length; i++) {
        let currentTab = allTabs[i];
        let currentSheetID = currentTab.getSheetId();

        // if loop sheet gID matches with request object gid than break the loop and return that sheet object
        if (currentSheetID == requestObject.gID) {
            sheet = currentTab;
            break;
        }
    }

    return sheet;
}

// get alphabet name of requested numeric column
// Example: Column #2 is going convert as Column B
function getLastColumChar(column) {
    var temp,
        letter = "";
    while (column > 0) {
        temp = (column - 1) % 26;
        letter = String.fromCharCode(temp + 65) + letter;
        column = (column - temp - 1) / 26;
    }
    return letter;
}
