function createPDF() {

    const styles = `

     table{
        border: 1px solid black;
        margin: 10px auto;
        width: 90%;
    }
     th, td{
      border: solid 1px #DDD;
      padding: 2px 3px;
      text-align: center;
    }
    `;


    let printContent = document.getElementById('table-content').innerHTML;
    // CREATE A WINDOW OBJECT.
    let a = window.open('', 'Print', 'height=700,width=700');

    a.document.write("<html><head><title>Print</title>");
    a.document.write(`<style>${styles}</style>`);
    a.document.write("</head><body> <h1> Lista zakupów </h1><br>");
    a.document.write(printContent);
    a.document.write("</body></html>");
    a.document.close();
    a.window.print();
    return true;
}