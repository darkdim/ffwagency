import { CreateTableCommand } from "@aws-sdk/client-dynamodb";
import { ddbClient } from "./libs/ddbClient.js";

// Set the parameters
export const params = {
    AttributeDefinitions: [
        {
            AttributeName: 'PAGE_ID',
            AttributeType: 'N'
        }
    ],
    KeySchema: [
        {
            AttributeName: 'PAGE_ID',
            KeyType: 'HASH'
        }
    ],
    ProvisionedThroughput: {
        ReadCapacityUnits: 1,
        WriteCapacityUnits: 1,
    },
    TableName: "PAGE_TABLE", //TABLE_NAME
    StreamSpecification: {
        StreamEnabled: false,
    },
};

export const run = async () => {
    try {
        const data = await ddbClient.send(new CreateTableCommand(params));
        console.log("Table Created", data);
        return data;
    } catch (err) {
        console.log("Error", err);
    }
};
run();
