import { fetchData } from './AwsFunctions';

const fetchDataFormDynamoDb = () => {
    fetchData('PAGE_TABLE')
}

function App() {
    return (
        <>
            <button onClick={() => fetchDataFormDynamoDb()}> Fetch </button>
        </>
    );
}

export default App;