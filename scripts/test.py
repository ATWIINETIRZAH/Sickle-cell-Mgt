

import pytesseract
import pdf2image
import pdfplumber

from PIL import Image
import pytesseract

# Path to a simple test image
image_path = r"C:\Users\user\Downloads\john3.png"

# Load the image
img = Image.open(image_path)

# Extract text
text = pytesseract.image_to_string(img)
print("Extracted Text:")
print(text)

# # print("All packages are imported successfully!")


# from PIL import Image
# import pytesseract

# # print("All packages are imported successfully!")

# # pytesseract.pytesseract.tesseract_cmd = r'c:\users\user\appdata\local\packages\pythonsoftwarefoundation.python.3.9_qbz5n2kfra8p0\localcache\local-packages\python39\site-packages'  # Adjust path if needed
# # text = pytesseract.image_to_string(Image.open("example_image.png"))
