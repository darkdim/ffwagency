// Import required AWS SDK clients and commands for Node.js
import { PutItemCommand } from "@aws-sdk/client-dynamodb";
import { ddbClient } from "./libs/ddbClient.js";

// Set the parameters
export const params = {
    TableName: "PAGE_TABLE",
    Item: {
        PAGE_ID: { N: "001" },
        PAGE_TITLE: { S: "The website coming soon..." },
        PAGE_DESCRIPTION: { S: "We will start working soon. More information will be available soon." },
        START_DATE: { S: "01.01.2023" },
    },
};

export const run = async () => {
    try {
        const data = await ddbClient.send(new PutItemCommand(params));
        console.log(data);
        return data;
    } catch (err) {
        console.error(err);
    }
};
run();
