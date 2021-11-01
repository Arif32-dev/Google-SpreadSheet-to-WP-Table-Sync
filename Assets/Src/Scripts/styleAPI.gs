// function testAPI(){
//   let requestObject = {
//     sheetID: '1BLPajAgkl9biIzA8avvzmXXtcXwfWmGWkksIyQP5Q-Q',
//     gID: '0',
//     action: 'getStyles'
//   }

//   // get sheet cell style like background color, text color
//   let response = getSheetCellStyles(requestObject);

//   Logger.log(JSON.stringify(response));
// }

function doGet(req) {
    let action = req.parameter.action;
    let sheetID = req.parameter.sheetID;
    let gID = req.parameter.gID;

    // if either sheetID parameter is not found then return error response
    if (!sheetID) {
        return ContentService.createTextOutput(
            JSON.stringify({
                status: 404,
                response: "sheetID required parameter is missing",
            })
        );
    }

    // If action is getStyles than fetch the sheet styles only
    if (action == "getStyles") {
        if (!gID) gID = 0;

        let requestObject = {
            sheetID,
            gID,
        };
        // get sheet cell style like background color, text color
        let sheetCellStyles = getSheetCellStyles(requestObject);

        return ContentService.createTextOutput(JSON.stringify(sheetCellStyles));
    }

    // If action is lastUpdatedTimestamp than fetch the sheet last updated time in unix format
    if (action == "lastUpdatedTimestamp") {
        let requestObject = {
            sheetID,
        };

        let getLastUpdatedtime = getLastUpdatedTimestamp(requestObject);

        return ContentService.createTextOutput(JSON.stringify(getLastUpdatedtime));
    }

    return ContentService.createTextOutput(
        JSON.stringify({
            status: 404,
            response: "sheetID required parameter is missing",
        })
    );
}

function getSheetCellStyles(requestObject) {
    let sheet = openSheetByID(requestObject);

    let lastColumn = sheet.getLastColumn();

    let lastRowIndex = sheet.getLastRow();

    lastColumn = getLastColumChar(lastColumn);

    let range = sheet.getRange(`A1:${lastColumn}${lastRowIndex}`);
    let bgColors = range.getBackgrounds();
    let fontColors = range.getFontColors();
    let fontFamily = range.getFontFamilies();
    let fontSize = range.getFontSizes();
    let fontWeights = range.getFontWeights();
    let fontStyles = range.getFontStyles();
    let textDecoration = range.getFontLines();
    let horizontalAlignments = range.getHorizontalAlignments();

    return {
        bgColors,
        fontColors,
        fontFamily,
        fontSize,
        fontWeights,
        fontStyles,
        textDecoration,
        horizontalAlignments,
    };
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

// Get the last updated time stamp in unix format
function getLastUpdatedTimestamp(requestObject) {
    try {
        let lastUpdatedTimestamp = DriveApp.getFileById(requestObject.sheetID).getLastUpdated();

        if (lastUpdatedTimestamp) {
            return {
                lastUpdatedTimestamp,
            };
        } else {
            return false;
        }
    } catch (error) {
        return error;
    }
}
