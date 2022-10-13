// Import required AWS SDK clients and commands for Node.js
import { DescribeTableCommand } from "@aws-sdk/client-dynamodb";
import { ddbClient } from "./libs/ddbClient.js";

// Set the parameters
export const params = { TableName: "PAGE_TABLE" }; //TABLE_NAME

export const run = async () => {
    try {
        const data = await ddbClient.send(new DescribeTableCommand(params));
        console.log("Success", data);
        console.log("Success", data.Table.KeySchema);
        return data;
    } catch (err) {
        console.log("Error", err);
    }
};
run();
