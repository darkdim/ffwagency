// Import required AWS SDK clients and commands for Node.js
import { GetItemCommand } from "@aws-sdk/client-dynamodb";
import { ddbClient } from "./libs/ddbClient.js";

// Set the parameters
export const params = {
    TableName: "PAGE_TABLE", //TABLE_NAME
    Key: {
        PAGE_ID: { N: "001" },
        PAGE_TITLE: { S: "The website coming soon..." },
    },
    ProjectionExpression: "PAGE_DESCRIPTION, START_DATE",
};

export const run = async () => {
    const data = await ddbClient.send(new GetItemCommand(params));
    console.log("Success", data.Item);
    return data;

};
run();
