const express = require("express");
const app = express();
const axios = require("axios");
const bodyParser = require("body-parser");

app.use(bodyParser.json());
app.use(express.static(__dirname + "/dist/"));

const API_URL = "http://pruebasgcm.telemovildata.com/api";

app.all(/^\/proxy\/.+$/, async (req, res) => {
  const url = `${API_URL}${req.path.replace("/proxy", "")}`;

  try {
    const result = await axios({
      url,
      // qs: req.query,
      method: req.method,
      data: req.body,
      headers: {
        "Content-Type": "application/json;charset=utf-8",
        "Access-Control-Allow-Origin": "*",
        ...(req.headers.authorization
          ? { Authorization: req.headers.authorization }
          : {})
      }
    });
    res.status(result.status).json(result.data);
  } catch (e) {
    res.status(500).json({ error: "An error occurred" });
  }
});

app.get(/.*/, (_, res) => res.sendFile(__dirname + "/dist/index.html"));

const port = process.env.PORT || 8080;
app.listen(port);

console.log("Listening on port: ", port);
