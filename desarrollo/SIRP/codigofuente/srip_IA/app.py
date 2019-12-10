import pandas as pd
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.metrics.pairwise import linear_kernel
import numpy as np
from random import randrange
from flask import Flask, request, jsonify
import json

ds = pd.read_csv("products.csv", encoding='utf-8')

app = Flask(__name__)

@app.route('/')
def index():
    return "<h1>Welcome to our server !!</h1>"

@app.route('/product/<product_id>', methods = ['POST'])
def get_products_recommedation(product_id):
    if request.method == 'POST':
        tf = TfidfVectorizer(analyzer='word', ngram_range=(1, 3), min_df=0)
        tfidf_matrix = tf.fit_transform(ds['brand_name'])

        cosine_similarities = linear_kernel(tfidf_matrix, tfidf_matrix) 
        results = {}

        for idx, row in ds.iterrows():
            similar_indices = cosine_similarities[idx].argsort()[:-100:-1] 
            similar_items = [(cosine_similarities[idx][i], ds['product_id'][i]) for i in similar_indices] 
            results[row['product_id']] = similar_items[1:]

if __name__ == '__main__':
    app.run(threaded=True, port=5000)