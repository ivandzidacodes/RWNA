using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.Services;
using System.Configuration;
using System.Data;
using System.Data.SqlClient;
using MySql.Data.MySqlClient;

namespace _1_wsdl_dnet
{
    /// <summary>
    /// Summary description for WebService1
    /// </summary>
    [WebService(Namespace = "http://tempuri.org/")]
    [WebServiceBinding(ConformsTo = WsiProfiles.BasicProfile1_1)]
    [System.ComponentModel.ToolboxItem(false)]
    // To allow this Web Service to be called from script, using ASP.NET AJAX, uncomment the following line. 
    // [System.Web.Script.Services.ScriptService]
    public class WebService1 : System.Web.Services.WebService
    {
        [WebMethod]
        public DataSet GetEmployeesByID(int userId)
        {

            string sql = "SELECT employees.emp_no, " +
                    "employees.first_name, " +
                    "employees.last_name, " +
                    "dept_emp.from_date, " +
                    "dept_emp.to_date, " +
                    "departments.dept_name FROM employees " +
                    "INNER JOIN dept_emp ON employees.emp_no = dept_emp.emp_no " +
                    "INNER JOIN departments ON dept_emp.dept_no = departments.dept_no " +
                    "WHERE employees.emp_no = " + userId;


            DataSet ds = new DataSet();
           
            string connStr = "server=localhost;user=root;database=sakila;port=3306;password=";
            MySqlConnection conn = new MySqlConnection(connStr);

            conn.Open();

            MySqlCommand cmd = new MySqlCommand(sql, conn);
            MySqlDataAdapter adapter = new MySqlDataAdapter(cmd);

            adapter.Fill(ds);
            conn.Close();

            return ds;

        }
    }

}
