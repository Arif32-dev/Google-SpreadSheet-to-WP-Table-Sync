// function testAPI(){
//   let requestObject = {
//     sheetID: '1t7MnIPlu_lU9srlftEvtSnSx3db3-hLctNXFao3wRVQ',
//     gID: '135572357',
//     action: 'getImages'
//   }

//   // get sheet cell style like background color, text color
//   let response = getImagesData(requestObject);

//   Logger.log(response);
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
        // get sheet cell style like background color, text color etc
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

    // If action is getImages than fetch the images that are inserted in google sheet as formula
    if (action == "getImages") {
        if (!gID) gID = 0;

        let requestObject = {
            sheetID,
            gID,
        };

        let imagesData = getImagesData(requestObject);

        return ContentService.createTextOutput(JSON.stringify(imagesData));
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

// Get all the data of inserted image from google sheet
function getImagesData(requestObject) {
    let sheet = openSheetByID(requestObject);

    let lastColumn = sheet.getLastColumn();

    let lastRowIndex = sheet.getLastRow();

    lastColumn = getLastColumChar(lastColumn);

    let range = sheet.getRange(`A1:${lastColumn}${lastRowIndex}`);

    let formulas = range.getFormulas();

    let returnValue = {};

    if (!formulas) return returnValue;

    let linkRegex =
        /((?:(http|https|Http|Https|rtsp|Rtsp):\/\/(?:(?:[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)\,\;\?\&\=]|(?:\%[a-fA-F0-9]{2})){1,64}(?:\:(?:[a-zA-Z0-9\$\-\_\.\+\!\*\'\(\)\,\;\?\&\=]|(?:\%[a-fA-F0-9]{2})){1,25})?\@)?)?((?:(?:[a-zA-Z0-9][a-zA-Z0-9\-]{0,64}\.)+(?:(?:aero|arpa|asia|a[cdefgilmnoqrstuwxz])|(?:biz|b[abdefghijmnorstvwyz])|(?:cat|com|coop|c[acdfghiklmnoruvxyz])|d[ejkmoz]|(?:edu|e[cegrstu])|f[ijkmor]|(?:gov|g[abdefghilmnpqrstuwy])|h[kmnrtu]|(?:info|int|i[delmnoqrst])|(?:jobs|j[emop])|k[eghimnrwyz]|l[abcikrstuvy]|(?:mil|mobi|museum|m[acdghklmnopqrstuvwxyz])|(?:name|net|n[acefgilopruz])|(?:org|om)|(?:pro|p[aefghklmnrstwy])|qa|r[eouw]|s[abcdeghijklmnortuvyz]|(?:tel|travel|t[cdfghjklmnoprtvwz])|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw]))|(?:(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9])\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[1-9]|0)\.(?:25[0-5]|2[0-4][0-9]|[0-1][0-9]{2}|[1-9][0-9]|[0-9])))(?:\:\d{1,5})?)(\/(?:(?:[a-zA-Z0-9\;\/\?\:\@\&\=\#\~\-\.\+\!\*\'\(\)\,\_])|(?:\%[a-fA-F0-9]{2}))*)?(?:\b|$)/g;

    formulas.forEach((rows, rowIndex) => {
        for (let i = 0; i < rows.length; i++) {
            let cellValue = rows[i];
            if (/(\=image|=IMAGE)/.test(cellValue)) {
                returnValue[`row_${rowIndex}_col_${i}`] = {
                    imgUrl: cellValue.match(linkRegex),
                    width: sheet.getColumnWidth(i + 1),
                    height: sheet.getRowHeight(rowIndex + 1),
                    rowIndex: rowIndex,
                    cellIndex: i,
                };
            }
        }
    });

    return returnValue;
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
    let temp,
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
