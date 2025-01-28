



# import sys
# from extract_obc import extract_pdf_text
# from parse_data import parse_cbc_data
# from extracted_data import save_cbc_data_to_db

# if len(sys.argv) != 3:
#     print("Usage: python main_script.py <pdf_path> <patient_name>")
#     sys.exit(1)

# pdf_path = sys.argv[1]
# patient_name = sys.argv[2]

# # Extract, parse, and save data
# extracted_text = extract_pdf_text(pdf_path)
# parsed_data = parse_cbc_data(extracted_text)
# save_cbc_data_to_db(patient_name, pdf_path, parsed_data)


import sys
from extract_obc import extract_pdf_text
from parse_data import parse_cbc_data
from extracted_data import save_cbc_data_to_db
import logging


# Ensure correct number of arguments
if len(sys.argv) != 3:
    print("Usage: python main_script.py <pdf_path> <patient_name>")
    sys.exit(1)

# Get arguments from the command line
pdf_path = sys.argv[1]
patient_name = sys.argv[2]

# Step 1: Extract text from the PDF
extracted_text = extract_pdf_text(pdf_path)
if not extracted_text:
    print("Failed to extract text from the PDF.")
    sys.exit(1)

# Step 2: Parse extracted text
cbc_metrics = parse_cbc_data(extracted_text)
if not cbc_metrics:
    print("Failed to parse data from the extracted text.")
    sys.exit(1)

# Step 3: Save parsed data to the database
save_cbc_data_to_db(patient_name, pdf_path, cbc_metrics)
# print("Processing complete. Data saved successfully.")

# Configure logging
logging.basicConfig(filename='error_log.txt', level=logging.ERROR)

try:
    # Processing code
    pass
except Exception as e:
    logging.error(f"Error: {e}")





