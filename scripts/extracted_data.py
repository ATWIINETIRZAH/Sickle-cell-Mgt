


# import sys
# sys.path.append("c:\users\user\appdata\local\packages\pythonsoftwarefoundation.python.3.9_qbz5n2kfra8p0\localcache\local-packages\python39\site-packages")  # Replace with the actual path

# import mysql.connector
# print("Import successful!")


import mysql.connector

  # Ensure correct import statement

def save_cbc_data_to_db(patient_name, file_path, cbc_data):
    try:
        # Establish database connection
        conn = mysql.connector.connect(
            host="localhost",
            user="root",  # Replace with your MySQL username
            password="",  # Replace with your MySQL password
            database="sickle_cell_management"
        )
        cursor = conn.cursor()

        # SQL query to insert data
        query = """
        INSERT INTO cbc_reports (patient_name, report_path, hemoglobin_level, wbc_count, upload_date)
        VALUES (%s, %s, %s, %s, NOW())
        """
        values = (patient_name, file_path, cbc_data.get('hemoglobin'), cbc_data.get('wbc_count'))
        cursor.execute(query, values)
        conn.commit()
        # print("Data saved successfully!")

    except mysql.connector.Error as e:
        print(f"Database error: {e}")

    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()


# if __name__ == "__main__":
#     # Example data for testing
#     patient_name = "John Doe"
#     file_path = "uploads/cbc_report.pdf"
#     cbc_data = {"hemoglobin": 13.5, "wbc_count": 7.8}  # Replace with actual parsed data

#     # Save example CBC data
#     save_cbc_data_to_db(patient_name, file_path, cbc_data)
