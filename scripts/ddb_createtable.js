import { CreateTableCommand } from "@aws-sdk/client-dynamodb";
import { ddbClient } from "./libs/ddbClient.js";

// Set the parameters
export const params = {
    AttributeDefinitions: [
        {
            AttributeName: 'PAGE_ID',
            AttributeType: 'N'
        },
        {
            AttributeName: 'PAGE_TITLE',
            AttributeType: 'S'
        },
        {
            AttributeName: 'START_DATE',
            AttributeType: 'S'
        }
    ],
    KeySchema: [
        {
            AttributeName: 'PAGE_ID',
            KeyType: 'HASH'
        },
        {
            AttributeName: 'PAGE_TITLE',
            KeyType: 'RANGE'
        }
    ],
    LocalSecondaryIndexes: [
        {
            IndexName: 'Index_PageID_StartDate',
            KeySchema: [
                {
                    AttributeName: 'PAGE_ID',
                    KeyType: 'HASH'
                },
                {
                    AttributeName: 'START_DATE',
                    KeyType: 'RANGE'
                }
            ],
            Projection: {
                ProjectionType: 'ALL',
            }
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
