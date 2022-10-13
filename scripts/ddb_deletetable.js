// Import required AWS SDK clients and commands for Node.js
import { DeleteTableCommand } from "@aws-sdk/client-dynamodb";
import { ddbClient } from "./libs/ddbClient.js";

// Set the parameters
export const params = { TableName: "TEST_TABLE" }; //TABLE_NAME

export const run = async () => {
    try {
        const data = await ddbClient.send(new DeleteTableCommand(params));
        console.log("Success, table deleted", data);
        // console.log(data.TableNames.join("\n"));
        return data;
    } catch (err) {
        console.error(err);
    }
};
run();
