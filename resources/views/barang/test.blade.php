@extends('layouts.main')

@section('content')
    <form action="">
        <table>
            <thead>
                <tr>
                    <th>header</th>
                    <th>header</th>
                    <th>header</th>
                    <th>header</th>
                    <th>header</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>cells1</td>
                    <td>cells2</td>
                    <td>cells3</td>
                    <td>cells4</td>
                    <td>cells5</td>
                </tr>
            </tbody>
        </table>
    </form>

    <script>
        let table = document.querySelector('form table');
        let tblPenawaranItem = table.getElementsByTagName('tbody')[0];
    
        let newRow = tblPenawaranItem.insertRow(-1);
        let cell1 = newRow.insertCell(0);
        let cell2 = newRow.insertCell(1);
        let cell3 = newRow.insertCell(2);
        
        cell1.innerHTML = 'zxc';
        cell2.innerHTML = 'aaa';
        cell3.innerHTML = 'lorem';
        console.log('zzzzzzzzzz', newRow);
    </script>
@endsection
