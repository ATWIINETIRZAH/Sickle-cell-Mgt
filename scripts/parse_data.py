import re
def parse_cbc_data(extracted_text):
    """
    Parses the extracted text from a CBC report to extract Hemoglobin and WBC values.

    Args:
        extracted_text (str): The text extracted from the PDF or image.

    Returns:
        dict: A dictionary containing Hemoglobin and WBC values if found.
    """
    cbc_data = {}

    # Debug: Display the extracted text
    # print(f"DEBUG: Extracted text: {extracted_text}")

    hemoglobin_match = re.search(r"HGB\s*\(?Hemoglobin\)?\s*[:\-]?\s*(\d+(\.\d+)?)", extracted_text, re.IGNORECASE)
    if hemoglobin_match:
        cbc_data['hemoglobin'] = float(hemoglobin_match.group(1))  # Convert to float for uniformity
        # print(f"DEBUG: Hemoglobin extracted: {cbc_data['hemoglobin']}")
    # else:
    #     print("DEBUG: Hemoglobin not found")

    # Regular expression for White Blood Cell (WBC)
    wbc_match = re.search(r"WBC\s*\(?White Blood Cell\)?\s*[:\-]?\s*(\d+(\.\d+)?)", extracted_text, re.IGNORECASE)
    if wbc_match:
        cbc_data['wbc_count'] = float(wbc_match.group(1))  # Convert to float for uniformity
        # print(f"DEBUG: WBC extracted: {cbc_data['wbc_count']}")
    # else:
    #     print("DEBUG: WBC not found")
    return cbc_data

# Example usage
if __name__ == "__main__":
    # Sample text for testing
    sample_text = """
    Patient Report
    HGB (Hemoglobin): 12.5
    WBC (White Blood Cell): 5.3
    """

    # Parse the sample text
    cbc_metrics = parse_cbc_data(sample_text)
    print("Parsed CBC Metrics:", cbc_metrics)

   
