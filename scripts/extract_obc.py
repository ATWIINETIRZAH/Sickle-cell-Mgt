





import pytesseract
from pdf2image import convert_from_path
import pdfplumber

# Path to Tesseract executable (modify based on your installation)
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR'

def extract_pdf_text(pdf_path):
    text_data = ""

    try:
        # Attempt to extract text using pdfplumber
        with pdfplumber.open(pdf_path) as pdf:
            for page in pdf.pages:
                text_data += page.extract_text()

        # Fallback to OCR if no text was extracted
        if not text_data.strip():
            print("No text found with pdfplumber. Using OCR...")
            images = convert_from_path(pdf_path)
            for image in images:
                text_data += pytesseract.image_to_string(image)

    except Exception as e:
        # print(f"Error reading PDF: {e}")
        print()

    return text_data

# Example usage
pdf_path = "cbc_report.pdf"  # Path to the PDF file
extracted_text = extract_pdf_text(pdf_path)
print(extracted_text)
