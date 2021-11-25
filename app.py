from os import write
from flask import Flask, render_template
import pdfplumber

# from pyzbar.pyzbar import decode
# from PIL import Image

# result = decode(Image.open('qrcode.png'))
# print(result)


from flask import request
from flask import jsonify
from flask_cors import CORS, cross_origin


import fitz
from PIL import Image
import glob
doc=""

        
app = Flask(__name__)
cors = CORS(app)
app.config['CORS_HEADERS'] = 'Content-Type'

@cross_origin()
@app.route('/background_check_throughputrate', methods=['GET', 'POST'])

# path = '3461.pdf'

def background_check_throughputrate():
    if request.method == 'POST':
        path  = request.form['file']
        result=""
        doc = fitz.open( path )
        for i in range(len(doc)):
            for img in doc.get_page_images(i):
                xref = img[0]
                pix = fitz.Pixmap(doc, xref)
                pix1 = pix
                pix1.set_origin(0, 0)
                if pix.width == pix.height:
                    if pix.n < 5:       # this is GRAY or RGB
                        pix.set_origin(100, 100)
                        pix1.copy(pix, (0, 0, 100, 100))
                        pix1.writePNG("output/mpdf-development/images/qr/%s%s.png" % (i, xref))
                    else:               # CMYK: convert to RGB first
                        pix1 = fitz.Pixmap(fitz.csRGB, pix)
                        pix1.writePNG("tmp/p-%s.png" % (xref))
                        pix1 = None
                    
            pix = None
        
        image_list = []
        left = 50
        top = 50
        right = 450
        bottom = 450
        
        for filename in glob.glob("output/mpdf-development/images/qr/*.png"):
            im = Image.open(filename)
            width, height = im.size
            

            if width == height:
                image_list.append(im)
        inter = image_list[0].crop((left, top, right, bottom))
        inter = inter.save('output/mpdf-development/images/qr/inter.png')
        left =33
        top = 33
        right = 467
        bottom = 467
        nat = image_list[1].crop((left, top, right, bottom))
        nat = nat.save('output/mpdf-development/images/qr/nat.png')
        
        with pdfplumber.open(path) as pdf:
            for  page  in pdf.pages:
                result += page.extract_text()
        return jsonify(result)




if __name__ == '__main__':
    app.run(host="0.0.0.0", port=5000)


    